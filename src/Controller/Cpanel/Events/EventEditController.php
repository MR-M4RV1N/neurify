<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\BookmarkRegister;
use App\Entity\Comment;
use App\Entity\Ensemble;
use App\Entity\EventRepeated;
use App\Entity\Image;
use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\AiCommentService;
use App\Service\ArrayFromItemsService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploader;
use App\Service\ImageUploadHandlerService;
use App\Service\InstructionFileUploader;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\WebhookPublisher;

class EventEditController extends AbstractController
{
    private $entityManager;
    private $userProgressUpdater;
    private $imageRemovalService;
    private $uploadHandler;
    private $imageUploader;
    private $instructionFileUploader;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserProgressUpdater $userProgressUpdater,
        ImageRemovalService $imageRemovalService,
        ImageUploadHandlerService $uploadHandler,
        ImageUploader $imageUploader,
        InstructionFileUploader $instructionFileUploader
    )
    {
        $this->entityManager = $entityManager;
        $this->userProgressUpdater = $userProgressUpdater;
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
        $this->imageUploader = $imageUploader;
        $this->instructionFileUploader = $instructionFileUploader;
    }

    /**
     * @Route("/cpanel/editor/events/new", name="app_event_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        Security $security,
        AiCommentService $aiCommentService,
        WebhookPublisher $webhookPublisher
    ): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $type = (int) $request->query->get('type', 1);
        // Очистка старых временных файлов
        $this->cleanupOldTempFiles();

        $event = $this->initializeEvent($user);
        $event->setTime(new \DateTimeImmutable());
        $event->setType($type);
        $event->setAiGenerated(false);
        $event->setAiCompleted(false);
        $event->setHidden(false);
        $form = $this->createForm(EventType::class, $event, ['user' => $user]);

        if ($this->handleEventForm($request, $form, $event, $user)) {
            return $this->redirectToRoute('app_account_statistics');
        }

        return $this->renderForm('cpanel/events/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'existing_images' => [], // Пустой массив для новых записей
        ]);
    }

    private function cleanupOldTempFiles(): void
    {
        $tempDirectory = $this->getParameter('image_upload_temp_directory');

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

    private function initializeEvent(User $user): Event
    {
        $event = new Event();
        $event->setUser($user);
        $event->setPublic(true);
        $event->setPinned(false);
        $event->setLang($user->getLang());
        $event->setDate(new \DateTime('now'));

        return $event;
    }


    private function handleEventForm(Request $request, $form, Event $event, User $user): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Загружаем файл через сервис
            /** @var UploadedFile|null $instructionFile */
            $instructionFile = $form->get('instructionFileUpload')->getData();

            if ($instructionFile) {
                $filename = $this->instructionFileUploader->upload($instructionFile);
                $event->setInstructionFile($filename);
            }

            // Получаем файлы из сессии
            $uploadedFiles = $request->getSession()->get('uploaded_images', []);

            if (empty($uploadedFiles)) {
                $request->getSession()->remove('uploaded_images');
            }

            foreach ($uploadedFiles as $filename) {
                // Формируем путь к файлу во временной директории пользователя
                $tempFilePath = $this->getParameter('image_upload_temp_directory') . '/' . $user->getId() . '/' . $filename;
                // Формируем путь к файлу в директории коллекций
                $finalFilePath = $this->getParameter('image_upload_directory_collections') . '/' . $filename;

                if (file_exists($tempFilePath) && !rename($tempFilePath, $finalFilePath)) {
                    throw new \RuntimeException(sprintf('Не удалось переместить файл %s в %s', $tempFilePath, $finalFilePath));
                }

                // Создаём сущность Image
                $image = new Image();
                $image->setUrl($filename);
                $event->addImage($image);
            }

            // Чистим сессию после сохранения
            $request->getSession()->remove('uploaded_images');

            // Сохраняем событие
            $this->entityManager->persist($event);
            $this->userProgressUpdater->updateUserProgress($user);
            $this->entityManager->flush();

            return true;
        }
        return false;
    }

    /**
     * @Route("/cpanel/editor/events/dropzone", name="app_event_dropzone", methods={"POST"})
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
        $tempDirectory = $this->getParameter('image_upload_temp_directory') . '/' . $user->getId();
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
     * @Route("/cpanel/editor/events/{id}/edit", requirements={"id"="\d+"}, name="app_event_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Event $event, Security $security): Response
    {
        $user = $security->getUser();
        if ($event->getUser() !== $user) {
            throw $this->createAccessDeniedException('Вы не можете редактировать это событие.');
        }

        // Загружаем форму
        $form = $this->createForm(EventType::class, $event, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Используем транзакцию для безопасности
            $this->entityManager->beginTransaction();

            try {
                // Обрабатываем временные файлы из Dropzone
                $this->processTemporaryFiles($request, $event, $user);

                // Удаляем выбранные изображения
                $this->processRemovedImages($request, $event);

                // Обрабатываем загруженные файлы
                $this->processUploadedFiles($form, $event);

                // Сохраняем изменения события
                $this->entityManager->persist($event);
                $this->entityManager->flush();

                $this->entityManager->commit();

                return $this->redirectToRoute('app_event_show', ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                // Откатываем транзакцию в случае ошибки
                $this->entityManager->rollback();
                throw $e;
            }
        }

        return $this->renderForm('cpanel/events/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'existing_images' => $event->getImages(),
        ]);
    }

    private function processTemporaryFiles(Request $request, Event $event, User $user): void
    {
        $tempFiles = $request->getSession()->get('uploaded_images', []);
        foreach ($tempFiles as $filename) {
            $tempFilePath = $this->getParameter('image_upload_temp_directory') . '/' . $user->getId() . '/' . $filename;
            $finalFilePath = $this->getParameter('image_upload_directory_collections') . '/' . $filename;
            if (file_exists($tempFilePath)) {
                rename($tempFilePath, $finalFilePath);
                // Создаём сущность Image
                $image = new Image();
                $image->setUrl($filename);
                $event->addImage($image);
            }
        }
        // Очищаем временные файлы из сессии
        $request->getSession()->remove('uploaded_images');
    }
    private function processRemovedImages(Request $request, Event $event): void
    {
        $removedImages = $request->request->get('removed_images', []);

        foreach ($removedImages as $imageId) {
            $image = $this->entityManager->getRepository(Image::class)->find($imageId);

            if ($image && $image->getEvent() === $event) {
                $filePath = $this->getParameter('image_upload_directory_collections') . '/' . $image->getUrl();

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->entityManager->remove($image);
            }
        }
    }

    private function processUploadedFiles($form, Event $event): void
    {
        // Обработка файла инструкции
        /** @var UploadedFile|null $instructionFile */
        $instructionFile = $form->get('instructionFileUpload')->getData();

        if ($instructionFile) {
            // По желанию: удаляем старый файл, если он остался (на случай,
            // если будет замена без отдельного delete)
            if ($event->getInstructionFile()) {
                $oldPath = $this->getParameter('instruction_files_directory') . '/' . $event->getInstructionFile();
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $filename = $this->instructionFileUploader->upload($instructionFile);
            $event->setInstructionFile($filename);
        }

        $uploadedFiles = $form->get('images')->getData();

        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                $filename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('image_upload_directory_collections'), $filename);

                // Создаём сущность Image
                $image = new Image();
                $image->setUrl($filename);
                $event->addImage($image);
            }
        }
    }


    /**
     * @Route("/cpanel/editor/events/images/{id}/delete", name="app_event_image_delete", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Image $image): JsonResponse
    {
        // Отладка текущего пользователя
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'Пользователь не авторизован'], Response::HTTP_FORBIDDEN);
        }

        try {
            $event = $image->getEvent();

            // Проверяем, что пользователь имеет права на удаление
            if ($event->getUser() !== $user) {
                return new JsonResponse(['success' => false, 'error' => 'Доступ запрещён'], Response::HTTP_FORBIDDEN);
            }

            // Удаляем файл изображения
            $filePath = $this->getParameter('image_upload_directory_collections') . '/' . $image->getUrl();
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
     * @Route("/cpanel/editor/events/{id}", requirements={"id"="\d+"}, name="app_event_delete", methods={"POST"})
     */
    public function deleteEvent(ManagerRegistry $doctrine, Security $security, Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            // Удаляем уведомления
            $notifications = $doctrine->getRepository(Notification::class)->findBy(['event' => $event]);
            foreach ($notifications as $notification) {
                $this->entityManager->remove($notification);
            }
            // Удаляем комментарии
            $comments = $doctrine->getRepository(Comment::class)->findBy(['event' => $event]);
            foreach ($comments as $comment) {
                $this->entityManager->remove($comment);
            }
            // Удаляем лайки
            $likes = $doctrine->getRepository(Like::class)->findBy(['targetId' => $event]);
            foreach ($likes as $like) {
                $this->entityManager->remove($like);
            }

            // Удаляем все связанные изображения
            foreach ($event->getImages() as $image) {
                // Удаляем файл изображения из файловой системы
                $filePath = $this->getParameter('image_upload_directory_collections') . '/' . $image->getUrl();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Удаляем изображение из базы данных
                $this->entityManager->remove($image);
            }

            // Удаляем BookmarkRegister
            $bookmarkRegister = $doctrine->getRepository(BookmarkRegister::class)->findOneBy(['event' => $event->getId()]);
            if ($bookmarkRegister) {
                $this->entityManager->remove($bookmarkRegister);
            }

            // Удаляем EventRepeated
            // в EventRepeated есть связь с Event (onDelete="CASCADE"), поэтому удаляем все связанные записи

            // Удаляем саму сущность события
            $eventRepository->remove($event, true);

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

    /**
     * @Route("/cpanel/editor/events/{id}/delete-instruction",
     *     requirements={"id"="\d+"},
     *     name="app_event_delete_instruction",
     *     methods={"POST"})
     */
    public function deleteInstructionFile(
        Request $request,
        Event $event
    ): Response
    {
        // Проверка CSRF
        if (!$this->isCsrfTokenValid('delete_instruction_'.$event->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token invalid.');
        }

        $filename = $event->getInstructionFile();

        if ($filename) {
            $filePath = $this->getParameter('instruction_files_directory') . '/' . $filename;

            // Удаляем файл, если существует
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Обнуляем поле
            $event->setInstructionFile(null);

            $this->getDoctrine()->getManager()->flush();
        }

        // Возвращаем на редактирование
        return $this->redirectToRoute('app_event_edit', ['id' => $event->getId()]);
    }
}