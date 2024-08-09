<?php

namespace App\Controller;

use App\Entity\Draft;
use App\Entity\Example;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailVerifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Security\AppCustomAuthenticator;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $userAuthenticator;
    private $authenticator;

    public function __construct(EmailVerifierService $emailVerifier, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userAuthenticator = $userAuthenticator;
        $this->authenticator = $authenticator;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();

        $descriptionLv = <<<EOT
Sveiki! Mani sauc [vārds]. Es dzīvoju [dzīvesvieta] un strādāju [darbavieta vai nodarbošanās]. Brīvajā laikā es nodarbojos ar [jūsu hobiju vai interesēm]. Esmu šeit lai atrastu jaunas izaicinājumu un uzdevumus, kas palīdzēs man paplašināt savas zināšanas un uzlabot prasmes.
EOT;
        $descriptionRu = <<<EOT
Привет! Меня зовут [имя]. Я живу в [место жительства] и работаю [место работы или занятие]. В свободное время я занимаюсь [вашим хобби или интересами]. Я здесь, чтобы найти новые вызовы и задачи, которые помогут мне расширить свои знания и улучшить навыки.
EOT;
        $descriptionEn = <<<EOT
Hello! My name is [name]. I live in [place of residence] and work at [workplace or occupation]. In my free time I enjoy [your hobby or interests]. I am here to find new challenges and tasks that will help me expand my knowledge and improve my skills.
EOT;
        if ($request->getLocale() == 'ru') {
            $description = $descriptionRu;
        } elseif ($request->getLocale() == 'en') {
            $description = $descriptionEn;
        } else {
            $description = $descriptionLv;
        }

        $user->setDescription($description);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                if (!in_array($image->guessExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    // Добавьте сообщение об ошибке к форме
                    $form->get('image')->addError(new FormError('Недопустимый формат файла.'));
                } else {
                    try {
                        $image->move(
                            $this->getParameter('profile_image_upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Добавьте сообщение об ошибке к форме
                        $form->get('image')->addError(new FormError('Ошибка загрузки файла.'));
                    }

                    $user->setImage($newFilename);
                }
            } else {
                $user->setImage('default-user.jpg');
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Делаем проверку username на уникальность
            $username = $form->get('username')->getData();
            $checkUsername = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
            if ($checkUsername) {
                $form->get('username')->addError(new FormError('Пользователь с таким логином уже существует.'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }
            // Установка роли пользователя
            $user->setRoles(['ROLE_USER']);
            // Установка языка пользователя
            $user->setLang($request->getLocale());
            // Установка прогресса пользователя
            $user->setProgress(0);

            // Если description и другие не обязательные поля не были изменены, то установить type = 0
            if (trim($user->getDescription()) == $description || trim($user->getDescription()) == '' || $user->getDescription() == null || $user->getLocation() == null || $user->getAge() == null) {
                $user->setType(0);
            } else {
                $user->setType(2);
            }

            // Генерация токена подтверждения
            $user->setConfirmationToken(Uuid::v4()->toRfc4122());
            $user->setIsVerified(false);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // Отправка подтверждения email
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $user->getEmail());

            // Из Example выбераем все записи у которых lang = $request->getLocale()
            $example = $entityManager->getRepository(Example::class)->findBy(['lang' => $request->getLocale()]);
            // Копируем все записи из Example в Draft
            foreach ($example as $item) {
                $draft = new Draft();
                $draft->setUser($user);
                $draft->setTitle($item->getTitle());
                $draft->setDescription($item->getDescription());
                $draft->setDate(new \DateTime());
                $draft->setImage($item->getImage());
                $entityManager->persist($draft);
                $entityManager->flush();
            }

            // Аутентификация пользователя
            $this->userAuthenticator->authenticateUser(
                $user,
                $this->authenticator,
                $request
            );

            return $this->redirectToRoute('app_cpanel_presentation');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-username", name="check_username")
     */
    public function checkUsername(Request $request, UserRepository $userRepository): JsonResponse
    {
        $username = $request->query->get('username');
        $user = $userRepository->findOneBy(['username' => $username]);

        return new JsonResponse(['exists' => $user !== null]);
    }
}
