<?php

namespace App\Controller\Cpanel;

use App\Entity\Level;
use App\Form\EventType;
use App\Form\UserAdditionType;
use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ProfileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
{
    private $profileService;
    private $translator;

    public function __construct(ProfileService $profileService, TranslatorInterface $translator)
    {
        $this->profileService = $profileService;
        $this->translator = $translator;
    }

    /**
     * @Route("/cpanel/profile/index", name="app_cpanel_profile")
     */
    public function index(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, TranslatorInterface $translator): Response
    {
        // Получаем текущего пользователя
        $user = $this->getUser();
        // Проверяем, аутентифицирован ли пользователь
        if (!$user) {
            $this->addFlash('error', $this->trans('You must be logged in to edit your profile.'));
            return $this->redirectToRoute('app_login');
        }
        // Проверяем, заполнены ли все поля профиля
        //if (!$this->profileService->checkProfileCompletion($user)) {
        //    return $this->redirectToRoute('app_cpanel_addition');
        //}

        // Создаем форму, используя форму UserType, связанную с данными текущего пользователя
        $form = $this->createForm(UserType::class, $user);

        // Обрабатываем отправку формы
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Перемещаем новый файл в директорию для загрузок
                    $imageFile->move(
                        $this->getParameter('profile_image_upload_directory'),
                        $newFilename
                    );

                    $originalImage = $user->getImage();
                    // Если у нас есть старый файл изображения, удаляем его
                    if ($originalImage) {
                        if($originalImage !== 'default-user.jpg') {
                            $oldFilePath = $this->getParameter('profile_image_upload_directory').'/'.$originalImage;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                    }

                    // Обновляем сущность новым именем файла изображения
                    $user->setImage($newFilename);
                } catch (FileException $e) {
                    // ... обработка исключения, если файл не может быть загружен
                }
            }
            // Делаем проверку username на уникальность
            $username = $form->get('username')->getData();
            $checkUsername = $doctrine->getRepository(User::class)->findOneBy(['username' => $username]);
            if ($checkUsername) {
                $form->get('username')->addError(new FormError('Пользователь с таким логином уже существует.'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }
            // Сохраняем изменения в базе данных
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Добавляем сообщение об успешном изменении профиля
            $this->addFlash('success', 'Your profile was successfully updated.');

            // Перенаправляем на страницу профиля или другую страницу
            return $this->redirectToRoute('app_cpanel_profile');
        }

        $myLevel = ceil($this->getUser()->getProgress() / 10);

        return $this->render('cpanel/profile/index.html.twig', [
            'form' => $form->createView(),
            'myLevel' => $doctrine->getRepository(Level::class)->findOneBy([
                'lang' => $this->getUser()->getLang(),
                'number' => $myLevel == 0 ? 1 : $myLevel
            ]),
        ]);
    }

    /**
     * @Route("/cpanel/profile/addition", name="app_cpanel_addition")
     */
    public function addition(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        // Получаем текущего пользователя
        $user = $this->getUser();
        // Проверяем, аутентифицирован ли пользователь
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to edit your profile.');
            return $this->redirectToRoute('app_login');
        }

        $user->setDescription($this->translator->trans('Example of description'));
        // Создаем форму, используя форму UserType, связанную с данными текущего пользователя
        $form = $this->createForm(UserAdditionType::class, $user);

        // Обрабатываем отправку формы
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Перемещаем новый файл в директорию для загрузок
                    $imageFile->move(
                        $this->getParameter('profile_image_upload_directory'),
                        $newFilename
                    );

                    $originalImage = $user->getImage();
                    // Если у нас есть старый файл изображения, удаляем его
                    if ($originalImage) {
                        if($originalImage !== 'default-user.jpg') {
                            $oldFilePath = $this->getParameter('profile_image_upload_directory').'/'.$originalImage;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                    }

                    // Обновляем сущность новым именем файла изображения
                    $user->setImage($newFilename);
                } catch (FileException $e) {
                    // ... обработка исключения, если файл не может быть загружен
                }
            }

            // Изменяем тип пользователя на 2 (пользователь с заполненным профилем)
            $user->setType(2);

            // Сохраняем изменения в базе данных
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Добавляем сообщение об успешном изменении профиля
            $this->addFlash('success', 'Your profile was successfully updated.');

            // Перенаправляем на страницу профиля или другую страницу
            return $this->redirectToRoute('app_cpanel_profile');
        }

        return $this->render('cpanel/profile/addition.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
