<?php

namespace App\Security;

use App\Data\DescriptionData;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;

/**
 * Google OAuth2 Authenticator
 */
class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(
        ParameterBagInterface $params,
        ClientRegistry $clientRegistry,
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->params = $params;
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/connect/google/check';
    }

    public function authenticate(Request $request): Passport
    {
        $this->logger->info('Fetching Google access token');
        $accessToken = $this->fetchAccessToken($this->getGoogleClient());

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $request) {
                try {
                    $googleUser = $this->getGoogleClient()->fetchUserFromToken($accessToken);
                } catch (\Exception $e) {
                    $this->logger->error('Google Auth Fetch User Error: ' . $e->getMessage());
                    throw new CustomUserMessageAuthenticationException('Не удалось получить данные от Google. Попробуйте снова.');
                }

                $email = $googleUser->getEmail();
                if (!$email) {
                    $this->logger->error('Google Auth Error: Email not provided by Google');
                    throw new CustomUserMessageAuthenticationException('От Google не получен ваш Email. Проверьте настройки приватности в аккаунте Google.');
                }

                $this->logger->info('Google user email: ' . $email);

                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    $this->logger->info('User not found, creating new user for email: ' . $email);
                    $user = $this->createUser($googleUser, $request);
                }

                return $user;
            }),
            [new RememberMeBadge()] // Указываем Symfony использовать "Remember Me" для аутентификации
        );
    }

    private function createUser($googleUser, Request $request): User
    {
        $user = new User();
        $user->setEmail($googleUser->getEmail());
        $user->setGoogleId($googleUser->getId());
        $user->setFirstname($googleUser->getFirstName());
        $user->setLastname($googleUser->getLastName());

        $username = $this->generateUsername($googleUser->getFirstName(), $googleUser->getLastName());
        $user->setUsername($username);

        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setType(2); // Изменено по просьбе: тип 2 по умолчанию

        $localeFull = $googleUser->getLocale() ?: 'en';
        $lang = explode('-', $localeFull)[0]; // Например: 'ru-RU' -> 'ru'

        // Разрешенные языки на платформе, фоллбэк на английский
        $allowedLangs = ['ru', 'en', 'lv', 'de'];
        if (!in_array($lang, $allowedLangs)) {
            $lang = 'en';
        }
        $user->setLang($lang);

        $user->setCountry($this->determineCountry($lang));
        $user->setDescription(DescriptionData::getDescription($lang));

        // Задаем ремесло(artisan) основываясь на языке пользователя
        $artisanMap = [
            'ru' => 'Обычный пользователь',
            'lv' => 'Parasts lietotājs',
            'en' => 'Regular user',
            'de' => 'Regulärer Benutzer'
        ];
        $user->setArtisan($artisanMap[$lang] ?? 'Regular user');

        $user->setImage('default.jpg');
        $user->setProgress(0);
        $user->setPassword(bin2hex(random_bytes(16))); // Установка случайного пароля

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error('Error saving new Google user to DB: ' . $e->getMessage());
            throw new CustomUserMessageAuthenticationException('Произошла внутренняя ошибка при создании вашего аккаунта.');
        }

        $request->getSession()->set('user_just_registered', true);

        return $user;
    }

    private function generateUsername(string $firstname, string $lastname): string
    {
        $date = new \DateTime();
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('Y');

        $safeFirst = !empty($firstname) ? $firstname[0] : 'U';
        $safeLast = !empty($lastname) ? $lastname : 'SER';

        $baseUsername = ucfirst($safeFirst) . '_' . strtoupper($safeLast) . '_' . $day . $month . $year;

        // Транслитерация, удаление недопустимых символов
        $username = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $baseUsername);
        $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);

        $originalUsername = $username;
        $counter = 1;

        // Предохранитель от бесконечного цикла, максимум 50 попыток, дальше uniqid
        while ($this->entityManager->getRepository(User::class)->findOneBy(['username' => $username])) {
            $username = $originalUsername . 'u' . $counter;
            $counter++;
            if ($counter > 50) {
                $username = $originalUsername . '_' . uniqid();
                break;
            }
        }

        return $username;
    }

    private function determineCountry(string $locale): string
    {
        $localeToCountry = [
            'ru' => 'Russia',
            'lv' => 'Latvia',
            'en' => 'United States',
            'fr' => 'France',
            'de' => 'Germany',
        ];

        return $localeToCountry[$locale] ?? 'Unknown';
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Логируем успешную аутентификацию
        $this->logger->info('Authentication success for user: ' . $token->getUser()->getUsername());

        // Check if the user was just registered via Google
        $session = $request->getSession();
        if ($session->get('user_just_registered')) {
            $session->remove('user_just_registered'); // clear the flag
            $targetRoute = 'app_cpanel_download';
        } else {
            $targetRoute = 'app_proposal_week';
        }

        // Создаем RedirectResponse
        $response = new RedirectResponse($this->router->generate($targetRoute));

        // Проверяем наличие "Remember Me" сервиса в атрибутах запроса
        if ($request->attributes->has(RememberMeServicesInterface::COOKIE_ATTR_NAME)) {
            /** @var RememberMeServicesInterface $rememberMeService */
            $rememberMeService = $request->attributes->get(RememberMeServicesInterface::COOKIE_ATTR_NAME);

            if ($rememberMeService instanceof RememberMeServicesInterface) {
                try {
                    $this->logger->info('Remember Me service detected, generating cookie.');

                    // Создаем новый токен для "Remember Me"
                    $rememberMeToken = new UsernamePasswordToken(
                        $token->getUser(),          // Аутентифицированный пользователь
                        $firewallName,              // Имя файрвола
                        $token->getUser()->getRoles() // Роли пользователя
                    );

                    // Вызываем RememberMeService для создания куки
                    $rememberMeService->loginSuccess($request, $response, $rememberMeToken);

                    $this->logger->info('Remember Me cookie successfully created.');
                } catch (\Exception $e) {
                    $this->logger->error('Failed to create Remember Me cookie: ' . $e->getMessage());
                }
            } else {
                $this->logger->warning('Remember Me service is not an instance of RememberMeServicesInterface.');
            }
        } else {
            $this->logger->warning('No Remember Me service attribute in request.');
        }

        // Возвращаем ответ (перенаправление на целевую страницу)
        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        $this->logger->error('Authentication failed: ' . $message);

        // Передаем сообщение об ошибке в сессию для вывода через flash messages
        if ($request->hasSession()) {
            $request->getSession()->getFlashBag()->add('danger', $message);
        }

        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $this->logger->info('Starting authentication process');
        return new RedirectResponse($this->router->generate('connect_google_start'));
    }

    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }
}
