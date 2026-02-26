<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Draft;
use App\Entity\Event;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Doctrine\ORM\QueryAdapter as PagerfantaAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\View\TwitterBootstrap4View;

/**
 * @Route("cpanel/public")
 */
class PublicReadController extends AbstractController
{
    private $eventShowPageRenderService;

    public function __construct(EventShowPageRenderService $eventShowPageRenderService)
    {
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/list", name="app_public_list", methods={"GET"})
     */
    public function list(
        Request $request,
        PictureRepository $pictureRepository,
        SimpleRepository $simpleRepository,
        EventRepository $eventRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
        $user = $this->getUser();
        // Получаем Picture
        $pictureRecords = $pictureRepository->createPublicPictureQueryBuilder(true);
        // Получаем Simple
        $simpleRecords = $simpleRepository->createPublicSimpleQueryBuilder(true);
        // Получаем Events
        $events = $eventRepository->createPublicEventsQueryBuilder(true);
        // Получаем MatrixMaps текущего пользователя
        $matrixMaps = $matrixMapRepository->createPublicMatrixMapQueryBuilder(true);
        // Объединяем и сортируем по дате+времени

        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        // Пагинация через ArrayAdapter
        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        // Обогащаем только события Event
        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
                return $item instanceof \App\Entity\Event;
            }),
            $user
        );

        return $this->render('cpanel/events/public/index.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(), // смешанный список
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta, // обязательно передаём pager
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_public_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
