<?php

// src/Controller/ResumeController.php
namespace App\Controller\Cpanel;

use App\Entity\Subscription;
use App\Repository\BookmarkRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Service\EventLastImagesService;
use App\Service\EventService;
use App\Service\PaginatorService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{
    private $paginatorService;
    private $params;

    public function __construct(
        PaginatorService $paginatorService,
        ContainerBagInterface $params
    ) {
        $this->paginatorService = $paginatorService;
        $this->params = $params;
    }

    private function getUserStatistics(
        $user,
        Request $request,
        EventRepository $eventRepository,
        LikeRepository $likeRepository,
        BookmarkRepository $bookmarkRepository = null
    ): array {
        $session = $request->getSession();
        $userJustRegistered = $session->get('user_just_registered', false);
        $session->remove('user_just_registered');
        $userEvents = $eventRepository->findBy(['user' => $user]);
        $eventIds = array_map(function ($e) {
            return $e->getId();
        }, $userEvents);

        $totalLikes = $likeRepository->countLikesForUserEvents($user, $eventIds);
        $allEvents = $eventRepository->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);

        return [
            'userJustRegistered' => $userJustRegistered,
            'totalLikes' => $totalLikes,
            'allEvents' => $allEvents,
            'level' => $level,
            'totalBookmarks' => $bookmarkRepository ? count($bookmarkRepository->findByUser($user)) : null,
        ];
    }

    /**
     * @Route("/cpanel/resume/picture", name="cpanel_resume_picture")
     */
    public function picture(
        Request $request,
        PictureRepository $pictureRepository,
        EventService $eventService,
        EventRepository $eventRepository,
        EventLastImagesService $eventLastImagesService,
        ImageRepository $imageRepository,
        BookmarkRepository $bookmarkRepository,
        EnsembleRepository $ensembleRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $user = $this->getUser();
        $selectedEnsemble = $ensembleRepository->findOneBy(['author' => $user, 'selected' => true]);
        $selectedEnsemble = $selectedEnsemble ? $selectedEnsemble->getId() : null;
        $pictureRecords = $pictureRepository->createPicturePortfolioQueryBuilder($this->getUser(), $selectedEnsemble);
        $simpleRecords = [];
        $matrixMaps = [];
        $events = [];
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->render('cpanel/resume/index.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
            'level' => $stats['level'],
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'lastEvents' => $eventLastImagesService->getEventsWithImages($user, 5),
            'subscriptions' => count($this->getDoctrine()->getRepository(Subscription::class)->findSubscriptionsForUser($user)),
            'followers' => count($this->getDoctrine()->getRepository(Subscription::class)->findFollowersForUser($user)),
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'userJustRegistered' => $stats['userJustRegistered'],
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/cpanel/resume/simple", name="cpanel_resume_simple")
     */
    public function simple(
        Request $request,
        SimpleRepository $simpleRepository,
        EventService $eventService,
        EventRepository $eventRepository,
        EventLastImagesService $eventLastImagesService,
        ImageRepository $imageRepository,
        BookmarkRepository $bookmarkRepository,
        EnsembleRepository $ensembleRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $user = $this->getUser();
        $pictureRecords = [];
        $selectedEnsemble = $ensembleRepository->findOneBy(['author' => $user, 'selected' => true]);
        $selectedEnsemble = $selectedEnsemble ? $selectedEnsemble->getId() : null;
        $simpleRecords = $simpleRepository->createSimplePortfolioQueryBuilder($this->getUser(), $selectedEnsemble);
        $matrixMaps = [];
        $events = [];
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->render('cpanel/resume/index.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'lastEvents' => $eventLastImagesService->getEventsWithImages($user, 5),
            'subscriptions' => count($this->getDoctrine()->getRepository(Subscription::class)->findSubscriptionsForUser($user)),
            'followers' => count($this->getDoctrine()->getRepository(Subscription::class)->findFollowersForUser($user)),
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'userJustRegistered' => $stats['userJustRegistered'],
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/cpanel/resume/matrix", name="cpanel_resume_matrix")
     */
    public function matrix(
        Request $request,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService,
        EventRepository $eventRepository,
        EventLastImagesService $eventLastImagesService,
        ImageRepository $imageRepository,
        BookmarkRepository $bookmarkRepository,
        EnsembleRepository $ensembleRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $user = $this->getUser();
        $pictureRecords = [];
        $simpleRecords = [];
        $selectedEnsemble = $ensembleRepository->findOneBy(['author' => $user, 'selected' => true]);
        $selectedEnsemble = $selectedEnsemble ? $selectedEnsemble->getId() : null;
        $matrixMaps = $matrixMapRepository->createMatrixMapPortfolioQueryBuilder($this->getUser(), $selectedEnsemble);
        $events = [];
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->render('cpanel/resume/index.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'lastEvents' => $eventLastImagesService->getEventsWithImages($user, 5),
            'subscriptions' => count($this->getDoctrine()->getRepository(Subscription::class)->findSubscriptionsForUser($user)),
            'followers' => count($this->getDoctrine()->getRepository(Subscription::class)->findFollowersForUser($user)),
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'userJustRegistered' => $stats['userJustRegistered'],
            'profile' => $user,
        ]);
    }

    /**
     * @Route("/cpanel/resume/events", name="cpanel_resume_events")
     */
    public function events(
        Request $request,
        EventRepository $eventRepository,
        EventService $eventService,
        EventLastImagesService $eventLastImagesService,
        ImageRepository $imageRepository,
        BookmarkRepository $bookmarkRepository,
        EnsembleRepository $ensembleRepository,
        LikeRepository $likeRepository
    ): Response
    {
        $user = $this->getUser();
        $pictureRecords = [];
        $simpleRecords = [];
        $matrixMaps = [];
        $selectedEnsemble = $ensembleRepository->findOneBy(['author' => $user, 'selected' => true]);
        $selectedEnsemble = $selectedEnsemble ? $selectedEnsemble->getId() : null;
        $events = $eventRepository->createEventsPortfolioQueryBuilder($this->getUser(), $selectedEnsemble);
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
                return $item instanceof \App\Entity\Event;
            }),
            $this->getUser()
        );

        $stats = $this->getUserStatistics($user, $request, $eventRepository, $likeRepository, $bookmarkRepository);

        return $this->render('cpanel/resume/index.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta,
            'countDefault' => $eventRepository->count(['user' => $user, 'ensemble' => null]),
            'countFeatured' => $eventRepository->count(['user' => $user, 'pinned' => true]),
            'lastEvents' => $eventLastImagesService->getEventsWithImages($user, 5),
            'subscriptions' => count($this->getDoctrine()->getRepository(Subscription::class)->findSubscriptionsForUser($user)),
            'followers' => count($this->getDoctrine()->getRepository(Subscription::class)->findFollowersForUser($user)),
            'totalLikes' => $stats['totalLikes'],
            'totalBookmarks' => $stats['totalBookmarks'],
            'allEvents' => $stats['allEvents'],
            'level' => $stats['level'],
            'userJustRegistered' => $stats['userJustRegistered'],
            'profile' => $user,
        ]);
    }
}
