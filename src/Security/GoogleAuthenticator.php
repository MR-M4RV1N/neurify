<?php

namespace App\Security;

use App\Entity\Draft;
use App\Entity\Example;
use App\Entity\Progress;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    private $clientRegistry;
    private $router;
    private $entityManager;
    private $logger;
    private $params;

    public function __construct(ParameterBagInterface $params, ClientRegistry $clientRegistry, RouterInterface $router, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
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
            new UserBadge($accessToken->getToken(), function() use ($accessToken) {
                $googleUser = $this->getGoogleClient()->fetchUserFromToken($accessToken);

                $email = $googleUser->getEmail();
                $this->logger->info('Google user email: ' . $email);

                // Поиск пользователя по email
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    $this->logger->info('User not found, creating new user for email: ' . $email);
                    // Создание нового пользователя
                    $user = new User();
                    $user->setEmail($email);
                    $user->setGoogleId($googleUser->getId());
                    $user->setFirstname($googleUser->getFirstName());
                    $user->setLastname($googleUser->getLastName());
                    // Получаем сгоднещнюю дату
                    $date = new \DateTime();
                    // Из даты получаем день
                    $day = $date->format('d');
                    // Из даты получаем месяц
                    $month = $date->format('m');
                    // Из даты получаем год
                    $year = $date->format('Y');
                    // Создаём username взяв первую букву firstname и добавив к нему первую большую букву lastname
                    $prepareUsername = ucfirst($googleUser->getFirstName()[0]).'_'.strtoupper($googleUser->getLastName()).'_'.$day.$month.$year;
                    $username = iconv('UTF-8', 'ASCII//TRANSLIT', $prepareUsername);
                    // Проверяем есть ли пользователь с таким username
                    do {
                        // Проверяем есть ли пользователь с таким username
                        $checkUsername = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
                        // Если пользователь с таким username существует, то добавляем к username случайное число
                        if ($checkUsername) {
                            $username = $username."u".rand(1, 100);
                        }
                    } while ($checkUsername);
                    // Устанавливаем username
                    $user->setUsername($username);
                    $user->setRoles(['ROLE_USER']);
                    $user->setIsVerified(true);
                    $user->setType(0);
                    $user->setLang('ru');
                    //$user->setDescription('...');
                    //$user->setLocation('...');
                    //$user->setAge(0);
                    $user->setImage('default.jpg');
                    $user->setProgress(0);
                    $user->setPassword(bin2hex(random_bytes(16))); // Установка случайного пароля
                    // Установите другие поля по мере необходимости
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    // Из Example выбераем все записи у которых lang = $request->getLocale()
                    $example = $this->entityManager->getRepository(Example::class)->findBy(['lang' => 'ru']);
                    // Копируем все записи из Example в Draft
                    foreach ($example as $item){
                        $draft = new Draft();
                        $draft->setUser($user);
                        $draft->setTitle($item->getTitle());
                        $draft->setDescription($item->getDescription());
                        $draft->setDate(new \DateTime());
                        $draft->setImage($item->getImage());
                        $this->entityManager->persist($draft);
                        $this->entityManager->flush();
                    }
                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->logger->info('Authentication success for user: ' . $token->getUser()->getUsername());
        return new RedirectResponse($this->router->generate('app_cpanel_presentation'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->logger->error('Authentication failed: ' . $exception->getMessage());
        return new RedirectResponse($this->router->generate('client_default'));
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
