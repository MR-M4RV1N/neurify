<?php

namespace App\Controller\Cpanel\Simple;

use App\Entity\BookmarkRegister;
use App\Entity\Comment;
use App\Entity\Ensemble;
use App\Entity\ImageSimple;
use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\Progress;
use App\Entity\Simple;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\SimpleType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\ProgressRepository;
use App\Repository\SimpleRepository;
use App\Service\ArrayFromItemsService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploader;
use App\Service\ImageUploadHandlerService;
use App\Service\UserProgressUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class SimpleEditController extends AbstractController
{
    private $entityManager;
    private $userProgressUpdater;
    private $imageRemovalService;
    private $uploadHandler;
    private $imageUploader;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserProgressUpdater $userProgressUpdater,
        ImageRemovalService $imageRemovalService,
        ImageUploadHandlerService $uploadHandler,
        ImageUploader $imageUploader
    )
    {
        $this->entityManager = $entityManager;
        $this->userProgressUpdater = $userProgressUpdater;
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
        $this->imageUploader = $imageUploader;
    }

    /**
     * @Route("/cpanel/simple/new", name="app_simple_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        // Очистка старых временных файлов
        $this->cleanupOldTempFiles();

        $simple = $this->initializeSimple($user);
        $simple->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(SimpleType::class, $simple, ['user' => $user]);

        if ($this->handleSimpleForm($request, $form, $simple, $user)) {
            return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/simple/new.html.twig', [
            'simple' => $simple,
            'form' => $form,
            'existing_images' => [], // Пустой массив для новых записей
        ]);
    }

    private function cleanupOldTempFiles(): void
    {
        $tempDirectory = $this->getParameter('image_upload_temp_directory_simple');

        if (!is_dir($tempDirectory)) {
            return; // Если корневой директории нет, выходим
        }

        $userDirectories = glob($tempDirectory . '/*', GLOB_ONLYDIR); // Получаем все директории пользователей

        $now = time();

        foreach ($userDirectories as $userDir) {
            $files = glob($userDir . '/*'); // Получаем все файлы в пользовательской директории

            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file)) > 3600) { // Файлы старше 1 часа
                    unlink($file); // Удаляем файл
                }
            }

            // Если директория пользователя пустая, удаляем её
            if (count(glob($userDir . '/*')) === 0) {
                rmdir($userDir);
            }
        }
    }

    private function initializeSimple(User $user): Simple
    {
        $simple = new Simple();
        $simple->setUser($user);
        $simple->setPublic(true);
        $simple->setCreatedAt(new \DateTimeImmutable());

        return $simple;
    }


    private function handleSimpleForm(Request $request, $form, Simple $simple, User $user): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Получаем файлы из сессии
            $uploadedFiles = $request->getSession()->get('uploaded_images', []);

            if (empty($uploadedFiles)) {
                $request->getSession()->remove('uploaded_images');
            }

            foreach ($uploadedFiles as $filename) {
                // Формируем путь к файлу во временной директории пользователя
                $tempFilePath = $this->getParameter('image_upload_temp_directory_simple') . '/' . $user->getId() . '/' . $filename;
                // Формируем путь к файлу в директории коллекций
                $finalFilePath = $this->getParameter('image_upload_directory_collections_simple') . '/' . $filename;

                if (file_exists($tempFilePath) && !rename($tempFilePath, $finalFilePath)) {
                    throw new \RuntimeException(sprintf('Не удалось переместить файл %s в %s', $tempFilePath, $finalFilePath));
                }

                // Создаём сущность Image
                $image = new ImageSimple();
                $image->setUrl($filename);
                $simple->addImage($image);
            }

            // Чистим сессию после сохранения
            $request->getSession()->remove('uploaded_images');

            // Сохраняем событие
            $this->entityManager->persist($simple);
            $this->userProgressUpdater->updateUserProgress($user);
            $this->entityManager->flush();

            return true;
        }
        return false;
    }

    /**
     * @Route("/cpanel/simple/dropzone", name="app_simple_dropzone", methods={"POST"})
     */
    public function upload(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'Необходима авторизация'], Response::HTTP_UNAUTHORIZED);
        }

        $file = $request->files->get('images'); // Dropzone отправляет файлы под этим именем

        if (!$file || !$file->isValid()) {
            return new JsonResponse(['success' => false, 'error' => 'Ошибка загрузки'], Response::HTTP_BAD_REQUEST);
        }

        // Создаём персональную директорию для временных файлов
        $tempDirectory = $this->getParameter('image_upload_temp_directory_simple') . '/' . $user->getId();
        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }

        // Используем handleUpload для обработки загрузки
        $this->uploadHandler->setUploadDirectory($tempDirectory);

        try {
            $newFilename = $this->uploadHandler->handleUploadFile($file);

            // Сохраняем имя файла в сессии
            $uploadedFiles = $request->getSession()->get('uploaded_images', []);
            $uploadedFiles[] = $newFilename;
            $request->getSession()->set('uploaded_images', $uploadedFiles);

            return new JsonResponse(['success' => true, 'filename' => $newFilename]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => 'Ошибка обработки файла: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/cpanel/simple/edit/{id}", requirements={"id"="\d+"}, name="app_simple_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Simple $simple, Security $security): Response
    {
        $user = $security->getUser();

        if ($simple->getUser() !== $user) {
            throw $this->createAccessDeniedException('Вы не можете редактировать это событие.');
        }

        // Загружаем форму
        $form = $this->createForm(SimpleType::class, $simple, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Используем транзакцию для безопасности
            $this->entityManager->beginTransaction();

            try {
                // Обрабатываем временные файлы из Dropzone
                $this->processTemporaryFiles($request, $simple, $user);

                // Удаляем выбранные изображения
                $this->processRemovedImages($request, $simple);

                // Обрабатываем загруженные файлы
                $this->processUploadedFiles($form, $simple);

                // Сохраняем изменения события
                $this->entityManager->persist($simple);
                $this->entityManager->flush();

                $this->entityManager->commit();

                return $this->redirectToRoute('app_events_list');
            } catch (\Exception $e) {
                // Откатываем транзакцию в случае ошибки
                $this->entityManager->rollback();
                throw $e;
            }
        }

        return $this->renderForm('cpanel/simple/edit.html.twig', [
            'simple' => $simple,
            'form' => $form,
            'existing_images' => $simple->getImages(),
        ]);
    }

    private function processTemporaryFiles(Request $request, Simple $simple, User $user): void
    {
        $tempFiles = $request->getSession()->get('uploaded_images', []);
        foreach ($tempFiles as $filename) {
            $tempFilePath = $this->getParameter('image_upload_temp_directory_simple') . '/' . $user->getId() . '/' . $filename;
            $finalFilePath = $this->getParameter('image_upload_directory_collections_simple') . '/' . $filename;
            if (file_exists($tempFilePath)) {
                rename($tempFilePath, $finalFilePath);
                // Создаём сущность Image
                $image = new ImageSimple();
                $image->setUrl($filename);
                $simple->addImage($image);
            }
        }
        // Очищаем временные файлы из сессии
        $request->getSession()->remove('uploaded_images');
    }
    private function processRemovedImages(Request $request, Simple $simple): void
    {
        $removedImages = $request->request->get('removed_images', []);

        foreach ($removedImages as $imageId) {
            $image = $this->entityManager->getRepository(ImageSimple::class)->find($imageId);

            if ($image && $image->getSimple() === $simple) {
                $filePath = $this->getParameter('image_upload_directory_collections_simple') . '/' . $image->getUrl();

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->entityManager->remove($image);
            }
        }
    }

    private function processUploadedFiles($form, Simple $simple): void
    {
        $uploadedFiles = $form->get('images')->getData();

        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                $filename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('image_upload_directory_collections_simple'), $filename);

                // Создаём сущность Image
                $image = new ImageSimple();
                $image->setUrl($filename);
                $simple->addImage($image);
            }
        }
    }


    /**
     * @Route("/cpanel/simple/images/{id}/delete", name="app_simple_image_delete", methods={"DELETE"})
     */
    public function deleteImage(Request $request, ImageSimple $image): JsonResponse
    {
        // Отладка текущего пользователя
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'Пользователь не авторизован'], Response::HTTP_FORBIDDEN);
        }

        try {
            $simple = $image->getSimple();

            // Проверяем, что пользователь имеет права на удаление
            if ($simple->getUser() !== $user) {
                return new JsonResponse(['success' => false, 'error' => 'Доступ запрещён'], Response::HTTP_FORBIDDEN);
            }

            // Удаляем файл изображения
            $filePath = $this->getParameter('image_upload_directory_collections_simple') . '/' . $image->getUrl();
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Удаляем изображение из базы данных
            $this->entityManager->remove($image);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            // Логируем ошибку и возвращаем сообщение
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/cpanel/simple/{id}", requirements={"id"="\d+"}, name="app_simple_delete", methods={"POST"})
     */
    public function deleteSimple(ManagerRegistry $doctrine, Security $security, Request $request, Simple $simple, SimpleRepository $simpleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$simple->getId(), $request->request->get('_token'))) {
            // Удаляем уведомления
//            $notifications = $doctrine->getRepository(Notification::class)->findBy(['simple' => $simple]);
//            foreach ($notifications as $notification) {
//                $this->entityManager->remove($notification);
//            }
            // Удаляем комментарии
//            $comments = $doctrine->getRepository(Comment::class)->findBy(['simple' => $simple]);
//            foreach ($comments as $comment) {
//                $this->entityManager->remove($comment);
//            }
            // Удаляем лайки
//            $likes = $doctrine->getRepository(Like::class)->findBy(['simple' => $simple]);
//            foreach ($likes as $like) {
//                $this->entityManager->remove($like);
//            }

            // Удаляем все связанные изображения
            foreach ($simple->getImages() as $image) {
                // Удаляем файл изображения из файловой системы
                $filePath = $this->getParameter('image_upload_directory_collections_simple') . '/' . $image->getUrl();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Удаляем изображение из базы данных
                $this->entityManager->remove($image);
            }

            // Удаляем BookmarkRegister
//            $bookmarkRegister = $doctrine->getRepository(BookmarkRegister::class)->findOneBy(['simple' => $simple->getId()]);
//            if ($bookmarkRegister) {
//                $this->entityManager->remove($bookmarkRegister);
//            }

            // Удаляем саму сущность события
            $simpleRepository->remove($simple, true);

            // Обновляем прогресс пользователя
            $entityManager = $doctrine->getManager();
            $item = $entityManager->getRepository(User::class)->find($security->getUser());
            if ($item) {
                $progress = $item->getProgress() - 1;
                $item->setProgress($progress);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
    }
}