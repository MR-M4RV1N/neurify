<?php

namespace App\Controller\Cpanel;

use App\Entity\Draft;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\EventShared;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\BookmarkRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepeatedRepository;
use App\Repository\EventRepository;
use App\Repository\EventSharedRepository;
use App\Repository\ImageRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Repository\SubscriptionRepository;
use App\Service\EventLastImagesService;
use App\Service\EventService;
use App\Service\EventStatisticsService;
use App\Service\PaginatorService;
use App\Service\StepwiseService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cpanel/account")
 */
class AccountController extends AbstractController
{
    private $paginatorService;
    private $params;

    public function __construct(PaginatorService $paginatorService, ContainerBagInterface $params)
    {
        $this->paginatorService = $paginatorService;
        $this->params = $params;
    }

    private function getUserStatistics($user, Request $request, EventRepository $eventRepo, LikeRepository $likeRepo, ?BookmarkRepository $bookmarkRepo): array
    {
        $userEvents = $eventRepo->findBy(['user' => $user, 'hidden' => false, 'aiGenerated' => false]);
        $eventIds = array_map(function ($e) {
            return $e->getId();
        }, $userEvents);

        $totalLikes = $likeRepo->countLikesForUserEvents($user, $eventIds);
        $allEvents = $eventRepo->count(['user' => $user]);

        return [
            'totalLikes' => $totalLikes,
            'allEvents' => $allEvents,
            'level' => min(floor($allEvents / 10), 20),
        ];
    }

    private function getCommonData($user, EventRepository $eventRepo, EventLastImagesService $lastImgService): array
    {
        return [
            'countDefault' => $eventRepo->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepo->count(['user' => $user, 'pinned' => true]),
            'lastEvents' => $lastImgService->getEventsWithImages($user, 5),
            'subscriptions' => count($this->getDoctrine()->getRepository(Subscription::class)->findSubscriptionsForUser($user)),
            'followers' => count($this->getDoctrine()->getRepository(Subscription::class)->findFollowersForUser($user)),
        ];
    }

    /**
     * @Route("/ensembles", name="app_account_ensembles", methods={"GET"})
     */
    public function list(Request $request, EventLastImagesService $lastImgService, ImageRepository $imgRepo, BookmarkRepository $bookmarkRepo, EnsembleRepository $ensembleRepo, EventRepository $eventRepo, LikeRepository $likeRepo): Response
    {
        $user = $this->getUser();
        $pager = $this->paginatorService->paginate($ensembleRepo->createEnsamblesQueryBuilder($user), $request->query->getInt('page', 1), 50);
        $stats = $this->getUserStatistics($user, $request, $eventRepo, $likeRepo, $bookmarkRepo);

        return $this->render('cpanel/account/ensembles/list.html.twig', array_merge([
            'ensembles' => $pager,
            'title' => 'Categories',
            'profile' => $user,
            'totalBookmarks' => $bookmarkRepo ? count($bookmarkRepo->findByUser($user)) : null,
        ], $stats, $this->getCommonData($user, $eventRepo, $lastImgService)));
    }

    /**
     * @Route("/classification", name="app_account_classification", methods={"GET"})
     */
    public function classification(
        Request $request,
        EventLastImagesService $lastImgService,
        EventRepository $eventRepo,
        LikeRepository $likeRepo,
        BookmarkRepository $bookmarkRepo
    ): Response {
        $user = $this->getUser();
        $year = (int) $request->query->get('year', date('Y'));

        // --- статистика как раньше
        $stats = $this->getUserStatistics(
            $user,
            $request,
            $eventRepo,
            $likeRepo,
            $bookmarkRepo
        );

        // --- классификация по power за год
        $classification = $eventRepo->countEventsByPowerForUserForYear($user, $year);

        return $this->render('cpanel/account/classification/classification.html.twig', array_merge([
            'classification' => $classification,
            'title'          => 'Classification',
            'profile'        => $user,
            'selectedYear'   => $year,
            'currentYear'    => (int) date('Y'),
        ], $stats, $this->getCommonData($user, $eventRepo, $lastImgService)));
    }

    /**
     * @Route("/all", name="app_account_all", methods={"GET"})
     */
    public function all(Request $request, EventLastImagesService $lastImgService, ImageRepository $imgRepo, EventRepository $eventRepo, LikeRepository $likeRepo, SimpleRepository $simpleRepo, PictureRepository $pictureRepo, MatrixMapRepository $matrixMapRepo, EventService $eventService, BookmarkRepository $bookmarkRepo): Response
    {
        $user = $this->getUser();
        $stats = $this->getUserStatistics($user, $request, $eventRepo, $likeRepo, $bookmarkRepo);

        $combined = $eventService->mergeAndSortEventAndMatrix(
            $pictureRepo->createPictureQueryBuilder($user),
            $simpleRepo->createSimpleQueryBuilder($user),
            $eventRepo->createEventsQueryBuilder($user),
            $matrixMapRepo->createMatrixMapQueryBuilder($user)
        );

        $pager = new Pagerfanta(new ArrayAdapter($combined));
        $pager->setMaxPerPage(10)->setCurrentPage($request->query->getInt('page', 1));
        $eventsToEnrich = array_filter($pager->getCurrentPageResults(), function ($item) {
            return $item instanceof Event;
        });

        $data = $eventService->enrichEvents($eventsToEnrich, $user);

        return $this->render('cpanel/account/all/list.html.twig', array_merge([
            'title' => 'Categories',
            'profile' => $user,
            'events' => $pager->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pager,
        ], $stats, $this->getCommonData($user, $eventRepo, $lastImgService)));
    }

    /**
     * @Route("/statistics", name="app_account_statistics", methods={"GET"})
     */
    public function statistics(
        Request $request,
        EventLastImagesService $lastImgService,
        ImageRepository $imgRepo,
        EventRepository $eventRepo,
        LikeRepository $likeRepo,
        BookmarkRepository $bookmarkRepo,
        EventStatisticsService $eventStatsService
    ): Response {
        $user = $this->getUser();
        $year = (int) $request->query->get('year', date('Y'));

        $stats = $this->getUserStatistics($user, $request, $eventRepo, $likeRepo, $bookmarkRepo);

        $session = $request->getSession();
        $userJustRegistered = $session->get('user_just_registered', false);
        $session->remove('user_just_registered');

        // Вызов сервиса — здесь вся логика по Event/EventShared
        $eventStats = $eventStatsService->buildEventsByMonthForUser($user, $year);
        $eventsCount   = $eventStats['eventsCount'];
        $eventsByMonth = $eventStats['eventsByMonth'];

        return $this->render('cpanel/account/statistics/list.html.twig', array_merge([
            'selectedYear' => $year,
            'currentYear'  => (int) date('Y'),
            'userJustRegistered' => $userJustRegistered,
            'title'              => 'Categories',
            'profile'            => $user,
            'eventsCount'        => $eventsCount,
            'eventsByMonth'      => $eventsByMonth,
        ], $stats, $this->getCommonData($user, $eventRepo, $lastImgService)));
    }

    /**
     * @Route("/stepwise/ensembles/{id}", requirements={"id"="\d+"}, name="stepwise_ensembles", methods={"GET"})
     */
    public function stepwiseEnsembles(
        User $user,
        EnsembleRepository $ensembleRepository,
        EventRepository $eventRepository
    ): Response {
        $selectedEnsembles = $ensembleRepository->findBy(['author' => $user, 'selected' => 1, 'aiGenerated' => false]);
        $countedEnsembles = count($selectedEnsembles);

        if ($countedEnsembles > 1) {
            $allEvents = $eventRepository->count(['user' => $user]);
            $level = min(floor($allEvents / 10), 20);
            return $this->render('cpanel/account/stepwise/ensembles.html.twig', [
                'ensembles' => $selectedEnsembles,
                'profile'   => $user,
                'level'     => $level,
            ]);
        }

        return $this->redirectToRoute('stepwise_single', ['id' => $user->getId()]);
    }

    /**
     * @Route("/stepwise/ensemble/open/{ensemble}", requirements={"ensemble"="\d+"}, name="stepwised_ensemble_open", methods={"GET"})
     */
    public function stepwiseEnsembleOpen(Ensemble $ensemble, ManagerRegistry $doctrine): Response
    {
        $quests = $doctrine->getRepository(Event::class)->findBy(['user' => $this->getUser(), 'ensemble' => $ensemble, 'task' => true], ['priority' => 'DESC', 'id' => 'ASC']);

        return $this->render('/cpanel/account/stepwise/list.html.twig', [
            'quests' => $quests,
            'profile' => $this->getUser(),
            'ensemble' => $ensemble,
            'level' => min(floor($doctrine->getRepository(Event::class)->count(['user' => $this->getUser()]) / 10), 20),
        ]);
    }

    /**
     * @Route("/stepwise/single/{id}", requirements={"id"="\d+"}, name="stepwise_single", methods={"GET"})
     */
    public function stepwiseSingle(User $user, ManagerRegistry $doctrine): Response
    {
        $ensemble = $doctrine->getRepository(Ensemble::class)->findOneBy(['author' => $user, 'selected' => 1]);
        $quests = $doctrine->getRepository(Event::class)->findBy(['user' => $user, 'ensemble' => $ensemble, 'task' => true], ['priority' => 'DESC', 'id' => 'ASC']);

        return $this->render('/cpanel/account/stepwise/list.html.twig', [
            'quests' => $quests,
            'profile' => $this->getUser(),
            'level' => min(floor($doctrine->getRepository(Event::class)->count(['user' => $this->getUser()]) / 10), 20),
        ]);
    }
}
