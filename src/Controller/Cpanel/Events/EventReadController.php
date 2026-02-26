<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Ensemble;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Repository\SimpleRepository;
use App\Service\ArrayFromItemsService;
use App\Service\EventLastImagesService;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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

class EventReadController extends AbstractController
{
    private $imageRemovalService;
    private $uploadHandler;
    private $eventShowPageRenderService;
    public function __construct(ImageRemovalService $imageRemovalService, ImageUploadHandlerService $uploadHandler, EventShowPageRenderService $eventShowPageRenderService)
    {
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/events/power/{power}", name="app_event_list_by_power", requirements={"power"="\d+"}, methods={"GET"})
     */
    public function listByPower(
        int $power,
        Request $request,
        EventRepository $eventRepo,
        EventLastImagesService $lastImgService,
        EventService $eventService
    ): Response {
        $user = $this->getUser();

        // защита
        if ($power < 0 || $power > 3) {
            throw $this->createNotFoundException('Invalid power level');
        }

        // --------------------------
        // 1. Получаем события
        // --------------------------
        $events = $eventRepo->findEventsByUserAndPower($user, $power);

        // --------------------------
        // 2. Пагинация (как в open())
        // --------------------------
        $adapter = new \Pagerfanta\Adapter\ArrayAdapter($events);
        $pager = new \Pagerfanta\Pagerfanta($adapter);
        $pager->setMaxPerPage(12);
        $pager->setCurrentPage($request->query->getInt('page', 1));

        // --------------------------
        // 3. ОБОГАЩЕНИЕ событий (КЛЮЧЕВО!)
        // --------------------------
        $data = $eventService->enrichEvents(
            $pager->getCurrentPageResults(),
            $user
        );

        // --------------------------
        // 4. Рендер
        // --------------------------
        return $this->render('cpanel/account/classification/list_by_power.html.twig', [
            'events'            => $pager->getCurrentPageResults(),
            'likedEvents'       => $data['likedEvents'],
            'bookmarkedEvents'  => $data['bookmarkedEvents'],
            'pager'             => $pager,

            // 👇 ВСЕ флаги контекста (важно!)
            'is_ensemble'       => false,
            'is_default'        => false,
            'is_featured'       => false,
            'is_events'         => true,   // ← нужный
            'is_public'         => false,
            'is_subscription'   => false,
            'is_user'           => false,
            'is_bookmark'       => false,

            'power'             => $power,
            'profile'           => $user,
            'title'             => 'Events by power',
            'lastEvents'        => $lastImgService->getEventsWithImages($user, 5),
        ]);

    }

    /**
     * @Route("/cpanel/editor/events/list", name="app_events_list", methods={"GET"})
     */
    public function list(
        Request $request,
        EventRepository $eventRepository,
        SimpleRepository $simpleRepository,
        PictureRepository $pictureRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
//        // Получаем Picture
//        $pictureRecords = $pictureRepository->createPictureQueryBuilder($this->getUser());
//        // Получаем Simple
//        $simpleRecords = $simpleRepository->createSimpleQueryBuilder($this->getUser());
//        // Получаем MatrixMaps
//        $matrixMaps = $matrixMapRepository->createMatrixMapQueryBuilder($this->getUser());
//        // Получаем Events
//        $events = $eventRepository->createEventsQueryBuilder($this->getUser());
//
//        // Объединяем и сортируем по дате создания
//        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);
//
//        // Пагинация через ArrayAdapter
//        $adapter = new ArrayAdapter($combined);
//        $pagerfanta = new Pagerfanta($adapter);
//        $pagerfanta->setMaxPerPage(10);
//        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));
//
//        $data = $eventService->enrichEvents(
//            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
//                return $item instanceof \App\Entity\Event;
//            }),
//            $this->getUser()
//        );
//
//        return $this->render('cpanel/events/event/index.html.twig', [
//            'events' => $pagerfanta->getCurrentPageResults(),
//            'likedEvents' => $data['likedEvents'],
//            'bookmarkedEvents' => $data['bookmarkedEvents'],
//            'pager' => $pagerfanta,
//        ]);
        return $this->redirectToRoute('app_account_statistics');
    }

    /**
     * @Route("/cpanel/editor/events/{id}", requirements={"id"="\d+"}, name="app_event_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
