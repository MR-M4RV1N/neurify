<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Bookmark;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\MatrixMapRepository;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkReadController extends AbstractController
{
    private $eventShowPageRenderService;

    public function __construct(EventShowPageRenderService $eventShowPageRenderService)
    {
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/cpanel/bookmark/list", name="app_bookmark_list", methods={"GET"})
     */
    public function list(
        Request $request,
        EventRepository $eventRepository,
        MatrixMapRepository $matrixMapRepository,
        BookmarkRepository $bookmarkRepository,
        EventService $eventService
    ): Response {
        $user = $this->getUser();

        // Получаем события из закладок
        $bookmarkedEventIds = $bookmarkRepository->findBookmarkedEventIdsByUser($user);

        $events = $eventRepository->createQueryBuilder('e')
            ->where('e.id IN (:ids)')
            ->setParameter('ids', $bookmarkedEventIds ?: [0]) // защита от пустого IN ()
            ->getQuery()
            ->getResult();
        // Получаем матрицы из закладок. В данном случае не используется.
        $matrixMaps = [];
        $simpleRecords = [];
        $pictureRecords = [];

        // Объединяем и сортируем
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        // Пагинация
        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($e) {
                return $e instanceof Event;
            }),
            $user
        );

        return $this->render('cpanel/events/bookmark/list.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => null,
            'bookmarkedEvents' => null,
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/cpanel/bookmark/show/{id}", requirements={"id"="\d+"}, name="app_bookmark_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
