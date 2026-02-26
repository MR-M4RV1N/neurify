<?php

namespace App\Controller\Client\Account;

use App\Entity\Chat;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\EventType;
use App\Form\MessagesType;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\LevelRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\EventLastImagesService;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Service\PaginatorService;

/**
 * @Route("/client/users")
 */
class UserReadController extends AbstractController
{
    private $paginatorService;
    private $eventShowPageRenderService;
    private $params;

    public function __construct(
        PaginatorService $paginatorService,
        EventShowPageRenderService $eventShowPageRenderService,
        ContainerBagInterface $params
    ) {
        $this->paginatorService = $paginatorService;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
        $this->params = $params;
    }

    private function getUserStatistics(
        $user,
        Request $request,
        EventRepository $eventRepository,
        LikeRepository $likeRepository,
        BookmarkRepository $bookmarkRepository = null
    ): array {
        $userEvents = $eventRepository->findBy(['user' => $user]);
        $eventIds = array_map(function ($e) {
            return $e->getId();
        }, $userEvents);

        $totalLikes = $likeRepository->countLikesForUserEvents($user, $eventIds);
        $allEvents = $eventRepository->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);

        return [
            'totalLikes' => $totalLikes,
            'allEvents' => $allEvents,
            'level' => $level,
            'totalBookmarks' => $bookmarkRepository ? count($bookmarkRepository->findByUser($user)) : null,
        ];
    }


    /**
     * @Route("/user/{id}", requirements={"id"="\d+"}, name="client_users_user", methods={"GET"})
     */
    public function user(
        Request $request,
        User $user,
        EnsembleRepository $ensembleRepository,
        ManagerRegistry $doctrine,
        PictureRepository $pictureRepository,
        SimpleRepository $simpleRepository,
        EventRepository $eventRepository,
        LevelRepository $levelRepository,
        SubscriptionRepository $subscriptionRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService,
        BookmarkRepository $bookmarkRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $currentUser = $this->getUser();
        $progress = $user->getProgress();
        $level = min(ceil($progress / 10), 10);

        // Проверка подписки: является ли текущий пользователь подписанным на выбранного пользователя
        $isFollowing = false;
        if ($currentUser && $currentUser !== $user) {
            $isFollowing = $subscriptionRepository->isUserFollowing($currentUser, $user);
        }

        // Получаем Picture
        $pictureRecords = $pictureRepository->createPublicPictureFromUsersQueryBuilder($user);
        // Получаем Simple
        $simpleRecords = $simpleRepository->createPublicSimpleFromUsersQueryBuilder($user);
        // Получаем Events
        $events = $eventRepository->createPublicEventsFromUsersQueryBuilder($user);
        // Получаем MatrixMaps текущего пользователя
        $matrixMaps = $matrixMapRepository->createPublicMatrixMapFromUsersQueryBuilder($user);

        // Объединяем и сортируем по дате+времени
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);
        // Создаем адаптер для Pagerfanta
        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));// Текущая страница

        // Обогащаем события с помощью сервиса
        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
                return $item instanceof \App\Entity\Event;
            }),
            $user
        );

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->renderForm('client/account/user/user.html.twig', [
            'totalEvents' => $eventRepository->count(['user' => $user]),
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'isFollowing' => $isFollowing, // Передаем статус подписки в шаблон
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'pager' => $pagerfanta,
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/categories/{id}", requirements={"id"="\d+"}, name="client_users_categories", methods={"GET"})
     */
    public function categories(
        Request $request,
        User $user,
        EnsembleRepository $ensembleRepository,
        ManagerRegistry $doctrine,
        EventRepository $eventRepository,
        LevelRepository $levelRepository,
        SubscriptionRepository $subscriptionRepository,
        EventService $eventService,
        BookmarkRepository $bookmarkRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $currentUser = $user;
        $progress = $user->getProgress();
        $level = min(ceil($progress / 10), 10);

        // Проверка подписки: является ли текущий пользователь подписанным на выбранного пользователя
        $isFollowing = false;
        if ($currentUser && $currentUser !== $user) {
            $isFollowing = $subscriptionRepository->isUserFollowing($currentUser, $user);
        }

        $queryBuilderEnsembles = $ensembleRepository->createEnsamblesClosedQueryBuilder($user);
        $paginatorCheck = new Paginator($queryBuilderEnsembles);
        $totalEnsembles = count($paginatorCheck);
        if ($totalEnsembles > 0) {
            $pagerfantaEnsembles = $this->paginatorService->paginate(
                $queryBuilderEnsembles,
                $request->query->getInt('page', 1),
                50
            );
        } else {
            $pagerfantaEnsembles = null;
        }

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->renderForm('client/account/user/categories.html.twig', [
            'totalEvents' => $eventRepository->count(['user' => $user]),
            'isFollowing' => $isFollowing, // Передаем статус подписки в шаблон
            'ensembles' => $pagerfantaEnsembles,
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/statistics/{id}", requirements={"id"="\d+"}, name="client_users_statistics", methods={"GET"})
     */
    public function statistics(
        Request $request,
        User $user,
        EnsembleRepository $ensembleRepository,
        ManagerRegistry $doctrine,
        EventRepository $eventRepository,
        LevelRepository $levelRepository,
        SubscriptionRepository $subscriptionRepository,
        EventService $eventService,
        EventLastImagesService $eventLastImagesService,
        ImageRepository $imageRepository,
        BookmarkRepository $bookmarkRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $currentUser = $user;
        $progress = $user->getProgress();
        $level = min(ceil($progress / 10), 10);

        // Проверка подписки: является ли текущий пользователь подписанным на выбранного пользователя
        $isFollowing = false;
        if ($currentUser && $currentUser !== $this->getUser()) {
            $isFollowing = $subscriptionRepository->isUserFollowing($currentUser, $currentUser);
        }

        // Находим в Event все записи, которые принадлежат текущему пользователю
        $events = $this->getDoctrine()->getRepository(Event::class)->findEventsForCurrentYearByUser($user->getId());

        // Группируем события по месяцам
        $eventsByMonth = [];
        foreach ($events as $event) {
            $month = $event->getDate()->format('F Y'); // Форматируем месяц и год
            if (!isset($eventsByMonth[$month])) {
                $eventsByMonth[$month] = [];
            }

            // Получаем первое изображение или задаём default.jpg
            $image = $event->getImages()->first() ? $event->getImages()->first()->getUrl() : 'default-min.jpg';

            $eventsByMonth[$month][] = [
                'event' => $event,
                'image' => $image,
            ];
        }

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->renderForm('client/account/user/statistics.html.twig', [
            'totalEvents' => $eventRepository->count(['user' => $user]),
            'profile' => $user,
            'isFollowing' => $isFollowing, // Передаем статус подписки в шаблон
            'eventsCount' => count($events),
            'eventsByMonth' => $eventsByMonth,
            'level' => $stats['level'],
        ]);
    }

    /**
     * @Route("/user/events/show/{id}", name="client_user_events_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}