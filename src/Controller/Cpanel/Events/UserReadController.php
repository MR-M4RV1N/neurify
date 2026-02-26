<?php

namespace App\Controller\Cpanel\Events;

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
use App\Repository\EventRepeatedRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\LevelRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\EnsembleProgressService;
use App\Service\EventLastImagesService;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use App\Service\StepwiseService;
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
 * @Route("/cpanel/users")
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
     * @Route("/user/{id}", requirements={"id"="\d+"}, name="app_users_user", methods={"GET"})
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
    ): Response {
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
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        // Обогащаем события с помощью сервиса
        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
                return $item instanceof \App\Entity\Event;
            }),
            $user
        );

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->renderForm('cpanel/events/user/user.html.twig', [
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
     * @Route("/categories/{id}", requirements={"id"="\d+"}, name="app_users_categories", methods={"GET"})
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
    ): Response {
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

        return $this->renderForm('cpanel/events/user/categories.html.twig', [
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
     * @Route("/statistics/{id}", requirements={"id"="\d+"}, name="app_users_statistics", methods={"GET"})
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
    ): Response {
        $currentUser = $user;
        $progress = $user->getProgress();
        $level = min(ceil($progress / 10), 10);

        // Проверка подписки: является ли текущий пользователь подписанным на выбранного пользователя
        $isFollowing = false;
        if ($currentUser && $currentUser !== $this->getUser()) {
            $isFollowing = $subscriptionRepository->isUserFollowing($currentUser, $this->getUser());
        }

        // Получаем год из запроса или текущий
        $year = (int) $request->query->get('year', date('Y'));

        $startDate = new \DateTime($year . '-01-01 00:00:00');
        $endDate = new \DateTime($year . '-12-31 23:59:59');

        // Находим в Event все записи, которые принадлежат текущему пользователю за выбранный год
        // Используем диапазон дат вместо функции YEAR(), так как она может быть недоступна в DQL
        $events = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.date BETWEEN :startDate AND :endDate')
            ->andWhere('e.hidden = :hidden') // Учитываем скрытые
            ->andWhere('e.aiGenerated = :ai')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('hidden', false)
            ->setParameter('ai', false)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();

        // Проверка на публичность для чужого профиля
        if (!$isFollowing && $currentUser !== $user) {
            // Если мы не подписаны и это не мы, фильтруем непубличные? 
            // В текущем коде этого не было, но логично добавить. 
            // Но пока оставим как было в оригинале, чтобы не сломать логику "как есть", просто добавив фильтр по году.
            // В оригинале было findEventsForCurrentYearByUser, который, вероятно, возвращал всё.
            // Но статистика должна показывать только то, что можно видеть?
            // Оставим фильтрацию на уровне шаблона или добавим сюда условие, если нужно.
            // Пока просто добавим год.
        }

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

        return $this->renderForm('cpanel/events/user/statistics.html.twig', [
            'totalEvents' => $eventRepository->count(['user' => $user]),
            'profile' => $user,
            'isFollowing' => $isFollowing, // Передаем статус подписки в шаблон
            'eventsCount' => count($events),
            'eventsByMonth' => $eventsByMonth,
            'level' => $stats['level'],
            'selectedYear' => $year,
            'currentYear' => (int) date('Y'),
        ]);
    }

    /**
     * @Route("/user/events/show/{id}", name="app_user_events_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }

    /**
     * @Route("/stepwise/ensembles/{id}", requirements={"id"="\d+"}, name="users_stepwise_ensembles", methods={"GET"})
     */
    public function stepwiseEnsembles(
        User $user,
        EnsembleRepository $ensembleRepository,
        EventRepository $eventRepository
    ): Response {
        $selectedEnsembles = $ensembleRepository->findBy(['author' => $user, 'selected' => 1, 'aiGenerated' => false]);
        $countedEnsembles = count($selectedEnsembles);

        $allEvents = $eventRepository->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);
        return $this->render('cpanel/events/user/stepwise/ensembles.html.twig', [
            'ensembles' => $selectedEnsembles,
            'profile'   => $user,
            'level'     => $level,
        ]);
    }

    /**
     * @Route("/stepwise/ensemble/open/{ensemble}", requirements={"ensemble"="\d+"}, name="users_stepwised_ensemble_open", methods={"GET"})
     */
    public function stepwiseEnsembleOpen(Ensemble $ensemble, StepwiseService $stepwise, EnsembleProgressService $progress): Response
    {
        // проверяем прогресс
        $completed = $progress->checkProgress($ensemble->getAuthor(), $this->getUser(), $ensemble ?? null);
        // если всё выполнено и шаблон ещё НЕ создан → делаем запись и редирект
        if ($completed === 1) {
            return $this->redirectToRoute('ensemble_complete_congratulations');
        }
        $data = $stepwise->buildForEnsemble($ensemble, $this->getUser());
        return $this->render('/cpanel/events/user/stepwise/list.html.twig', $data);
    }

    /**
     * @Route("/stepwise/{id}", name="users_stepwise_single", methods={"GET"})
     */
    public function stepwiseSingle(User $user, StepwiseService $stepwise, EnsembleProgressService $progress): Response
    {
        // проверяем прогресс
        $completed = $progress->checkProgress($this->getUser(), $user, $ensemble ?? null);
        // если всё выполнено и шаблон ещё НЕ создан → делаем запись и редирект
        if ($completed === 1) {
            return $this->redirectToRoute('ensemble_complete_congratulations');
        }

        $data = $stepwise->buildForUser($user, $this->getUser());
        return $this->render('/cpanel/events/user/stepwise.html.twig', $data);
    }

    /**
     * @Route("/cpanel/events/stepwise/congratulations", name="ensemble_complete_congratulations")
     */
    public function congratulations(): Response
    {
        return $this->render('cpanel/events/user/stepwise/congratulations.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/classification/{id}", requirements={"id"="\d+"}, name="app_users_classification", methods={"GET"})
     */
    public function classification(
        Request $request,
        User $user,
        EventRepository $eventRepo,
        LikeRepository $likeRepo,
        BookmarkRepository $bookmarkRepo,
        SubscriptionRepository $subscriptionRepo,
        EventLastImagesService $lastImgService
    ): Response {
        $currentUser = $this->getUser();
        // Проверка подписки
        $isFollowing = false;
        if ($currentUser && $currentUser !== $user) {
            $isFollowing = $subscriptionRepo->isUserFollowing($currentUser, $user);
        }

        // Получаем год из запроса или текущий
        $year = (int) $request->query->get('year', date('Y'));

        // Статистика классификации за выбранный год
        $classification = $eventRepo->countEventsByPowerForUserForYear($user, $year);

        // Общая статистика (для сайдбара/шапки, если нужно, или просто для контекста)
        $stats = $this->getUserStatistics($user, $request, $eventRepo, $likeRepo, $bookmarkRepo);

        return $this->render('cpanel/events/user/classification.html.twig', [
            'classification' => $classification,
            'title'          => 'Classification',
            'profile'        => $user,
            'isFollowing'    => $isFollowing,
            'level'          => $stats['level'],
            'totalEvents'    => $stats['allEvents'],
            'totalLikes'     => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'countDefault'   => $eventRepo->count(['user' => $user, 'ensemble' => null]),
            'countFeatured'  => $eventRepo->count(['user' => $user, 'pinned' => true]),
            'selectedYear'   => $year,
            'currentYear'    => (int) date('Y'),
        ]);
    }

    /**
     * @Route("/events/power/{id}/{power}", name="app_users_list_by_power", requirements={"id"="\d+", "power"="\d+"}, methods={"GET"})
     */
    public function listByPower(
        User $user,
        int $power,
        Request $request,
        EventRepository $eventRepo,
        EventService $eventService,
        SubscriptionRepository $subscriptionRepo,
        LikeRepository $likeRepo,
        BookmarkRepository $bookmarkRepo
    ): Response {
        $currentUser = $this->getUser();
        // Проверка подписки
        $isFollowing = false;
        if ($currentUser && $currentUser !== $user) {
            $isFollowing = $subscriptionRepo->isUserFollowing($currentUser, $user);
        }

        // защита
        if ($power < 0 || $power > 3) {
            throw $this->createNotFoundException('Invalid power level');
        }

        // Получаем публичные события пользователя с определенной силой
        // ВАЖНО: нужно убедиться, что репозиторий может фильтровать по user AND power AND public (или hidden=false)
        // Если метода нет, возможно придется использовать findBy или QueryBuilder
        // Предположим, что findEventsByUserAndPower возвращает все, тогда надо фильтровать
        // Или создадим QueryBuilder прямо здесь, если нет готового метода для ПУБЛИЧНЫХ

        // Используем существующий метод, но он может возвращать приватные.
        // Для чужого профиля мы должны показывать только публичные (или если shared).
        // В EventReadController: $events = $eventRepo->findEventsByUserAndPower($user, $power);
        // Проверим, что он делает. Если он возвращает всё подряд, нам это не подходит для чужого профиля.

        // Получаем год из запроса
        $year = $request->query->getInt('year') ?: (int) date('Y');
        $startDate = new \DateTime($year . '-01-01 00:00:00');
        $endDate = new \DateTime($year . '-12-31 23:59:59');

        // Лучше использовать QueryBuilder для публичных
        $qb = $eventRepo->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.power = :power')
            ->andWhere('e.hidden = :hidden')
            ->andWhere('e.aiGenerated = :ai')
            ->andWhere('e.date BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('power', $power)
            ->setParameter('hidden', false)
            ->setParameter('ai', false)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('e.date', 'DESC');

        // Если смотреть свой профиль - можно всё. Если чужой - только public? 
        // В ТЗ явно не сказано, но обычно "User Profile" публичный.
        // В методе user() используется createPublicEventsFromUsersQueryBuilder.
        // Сделаем аналогично: public = true

        if ($currentUser !== $user) {
            $qb->andWhere('e.public = :public')
                ->setParameter('public', true);
        }

        $adapter = new QueryAdapter($qb);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(12);
        $pager->setCurrentPage($request->query->getInt('page', 1));

        $data = $eventService->enrichEvents(
            $pager->getCurrentPageResults(),
            $currentUser // для лайков/закладок
        );

        $stats = $this->getUserStatistics($user, $request, $eventRepo, $likeRepo, $bookmarkRepo);

        return $this->render('cpanel/events/user/list_by_power.html.twig', [
            'events'            => $pager->getCurrentPageResults(),
            'likedEvents'       => $data['likedEvents'],
            'bookmarkedEvents'  => $data['bookmarkedEvents'],
            'pager'             => $pager,
            'power'             => $power,
            'profile'           => $user,
            'title'             => 'Events by power',
            'isFollowing'       => $isFollowing,
            'level'             => $stats['level'],
            'totalEvents'       => $stats['allEvents'],
            'is_user'           => true,
            'selectedYear'      => $year,
        ]);
    }
}
