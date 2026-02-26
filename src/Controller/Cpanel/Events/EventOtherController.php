<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Bookmark;
use App\Entity\BookmarkRegister;
use App\Entity\Ensemble;
use App\Entity\EventRepeated;
use App\Entity\Image;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\WeekChallenge;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploader;
use App\Service\ImageUploadHandlerService;
use App\Service\NotificationService;
use App\Service\UserProgressUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/cpanel/editor/events")
 */
class EventOtherController extends AbstractController
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
     * @Route("/last", name="app_events_last", methods={"GET"})
     */
    public function last(Request $request, EventRepository $eventRepository): Response
    {
        // Выясняем сколько записей в $eventRepository у пользователя
        $count = $eventRepository->count(['user' => $this->getUser()]);
        // Узнаём остаток от деления
        $remainder = $count % 10;
        // Выбираем из $eventRepository последние $remainder записей
        $queryBuilder = $eventRepository->createEventsRemainderQueryBuilder($this->getUser(), $remainder);

        return $this->render('cpanel/events/event/last.html.twig', [
            'events' => $queryBuilder->getQuery()->getResult()
        ]);
    }

    /**
     * @Route("/result", name="app_events_result")
     */
    public function result(ManagerRegistry $doctrine): Response
    {
        $progress = $doctrine->getRepository(Progress::class)->findOneBy(['user' => $this->getUser(), 'active' => true]);

        $items = $doctrine->getRepository(Wording::class)->findBy(['name' => 'result', 'lang' => $this->getUser()->getLang()]);
        $arr = [];
        foreach ($items as $i) {
            $arr[] = [
                'text' => $i->getText()
            ];
        }

        return $this->render('cpanel/events/event/other/result.html.twig', [
            'progress' => $progress,
            'items' => $arr
        ]);
    }

    /**
     * @Route("/my_public_events", name="app_events_list_public", methods={"GET"})
     */
    public function listPublic(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(['user' => $this->getUser(), 'public' => true]);

        return $this->render('cpanel/events/event/other/public.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/to_public/{id}", name="app_event_to_public", requirements={"id"="\d+"})
     */
    public function toPublic(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPublic(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @Route("/retract_public/{id}", name="app_event_retract_public", requirements={"id"="\d+"})
     */
    public function retractPublic(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPublic(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @Route("/pin/{id}", name="app_event_pin", requirements={"id"="\d+"})
     */
    public function pin(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPinned(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @Route("/unpin/{id}", name="app_event_unpin", requirements={"id"="\d+"})
     */
    public function unpin(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $entityManager->getRepository(Event::class)->find($id);
        $item->setPinned(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @Route("/new/{id}", name="app_event_new_in_ensemble", methods={"GET", "POST"})
     */
    public function newEvent(Request $request, ManagerRegistry $doctrine, Security $security, SluggerInterface $slugger, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $event = new Event();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }
        $event->setUser($user);
        $event->setPublic(false);
        $event->setPinned(0);
        $event->setLang($user->getLang());
        $event->setEnsemble($doctrine->getRepository(Ensemble::class)->find($id));

        $form = $this->createForm(EventType::class, $event, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_events'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $event->setImage($imageName);

            $entityManager->persist($event);

            // Обновляем ячейку progress в таблице User
            $item = $entityManager->getRepository(User::class)->find($user);
            $progress = $item->getProgress() + 1;
            $item->setProgress($progress);
            $entityManager->persist($item);

            $entityManager->flush();

            return $this->redirectToRoute('app_ensemble_open', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/events/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/bookmark/complete/{id}", requirements={"id"="\d+"}, name="app_bookmark_complete", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function completeItem(ManagerRegistry $doctrine, Request $request, int $id, Security $security, NotificationService $notificationService): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        $bookmarkEvent = $doctrine->getRepository(Event::class)->find($id);
        if (!$bookmarkEvent) {
            throw $this->createNotFoundException('No entity found for id ' . $id);
        }

        $entityManager = $doctrine->getManager();
        $event = new Event();
        $event->setUser($user);
        $event->setTitle($bookmarkEvent->getTitle());
        $event->setDescription($bookmarkEvent->getDescription());
        $event->setDate(new \DateTime());
        $event->setPublic(true);
        $event->setPinned(false);

        if (method_exists($user, 'getLang')) {
            $event->setLang($user->getLang());
        }

        $form = $this->createForm(EventType::class, $event, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);

            // Создаём уведомление, если у владельца события есть пользователь
            $eventOwner = $bookmarkEvent->getUser();
            if ($eventOwner) {
                $notificationService->createNotification(
                    $eventOwner,
                    $user,
                    $event,
                    'repeat'
                );
            }

            if($bookmarkEvent->getUser() == $doctrine->getRepository(User::class)->find(65)) {
                $weekChallenge = $doctrine->getRepository(WeekChallenge::class)->findOneBy(['event' => $bookmarkEvent->getId()]);
                // Создаём запись в BookmarkRegister
                $bookmarkRegister = new BookmarkRegister();
                $bookmarkRegister->setUser($user);
                $bookmarkRegister->setEvent($event);
                $bookmarkRegister->setWeekChallenge($weekChallenge);
                $entityManager->persist($bookmarkRegister);
            }

            // Удаляем закладку (если она есть)
            $bookmark = $doctrine->getRepository(Bookmark::class)->findOneBy(['user' => $user, 'event' => $id]);
            if ($bookmark) {
                $entityManager->remove($bookmark);
            }

            // Вызываем flush() один раз
            $entityManager->flush();

            return $this->redirectToRoute('app_account_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/bookmark/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/repeate/{id}", requirements={"id"="\d+"}, name="app_repeate", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function repeatItem(
        ManagerRegistry $doctrine,
        Request $request,
        int $id,
        Security $security,
        NotificationService $notificationService
    ): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }

        /** @var Event|null $repeatEvent */
        $repeatEvent = $doctrine->getRepository(Event::class)->find($id);
        if (!$repeatEvent) {
            throw $this->createNotFoundException('No entity found for id ' . $id);
        }

        // 1) Очистка старых временных файлов — как в new()
        $this->cleanupOldTempFiles();

        // 2) Используем ту же инициализацию, что и в new(), чтобы получить uploadSessionId и прочие дефолты
        //    Если initializeEvent недоступен — можно заменить на new Event() и выставление базовых полей вручную.
        $event = $this->initializeEvent($user);

        // 3) Перенос нужных полей из повторяемого события + твои дефолты
        $event->setTitle($repeatEvent->getTitle());
        $event->setDate(new \DateTime());
        $event->setTime(new \DateTimeImmutable());
        $event->setPublic(true);
        $event->setPinned(false);
        if($repeatEvent->isAiGenerated() == true) {
            $event->setAiGenerated(true);
            $event->setAiCompleted(true);
            $event->setHidden(true);
        }
        else {
            $event->setAiGenerated(false);
            $event->setAiCompleted(false);
            $event->setHidden(false);
        }

        // Если хочешь сохранить тип исходного события:
        if (method_exists($repeatEvent, 'getType') && method_exists($event, 'setType')) {
            $event->setType($repeatEvent->getType());
        }
        if (method_exists($user, 'getLang') && method_exists($event, 'setLang')) {
            $event->setLang($user->getLang());
        }

        // 4) Форма — как в new()
        $form = $this->createForm(EventType::class, $event, ['user' => $user]);

        // 5) Унифицированная обработка формы и загрузок (перенос временных файлов, создание EventImage, persist/flush)
        if ($this->handleEventForm($request, $form, $event, $user)) {
            $em = $doctrine->getManager();

            // 6) Уведомление владельца исходного события
            $eventOwner = $repeatEvent->getUser();
            if ($eventOwner) {
                $notificationService->createNotification($eventOwner, $user, $event, 'repeat');
            }

            // 7) Запись о повторении
            $eventRepeated = new EventRepeated();
            $eventRepeated->setUser($user);
            $eventRepeated->setEvent($repeatEvent);
            $eventRepeated->setReplyEvent($event);
            $em->persist($eventRepeated);
            $em->flush();

            // 8) Редирект после успеха
            return $this->redirectToRoute(
                'users_stepwise_single',
                ['id' => $repeatEvent->getUser()->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // 9) Рендер — как в new(); для повторения показываем пустой список картинок (или подставь $repeatEvent->getImages(), если нужно превью)
        return $this->renderForm('cpanel/repeat/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'existing_images' => [], // или $repeatEvent->getImages()
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
}