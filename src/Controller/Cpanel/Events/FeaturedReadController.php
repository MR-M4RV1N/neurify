<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Ensemble;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use App\Service\ImageRemovalService;
use App\Service\ImageUploadHandlerService;
use Doctrine\Persistence\ManagerRegistry;
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

/**
 * @Route("/cpanel/featured/events")
 */
class FeaturedReadController extends AbstractController
{
    private $uploadHandler;
    private $eventShowPageRenderService;
    public function __construct(ImageUploadHandlerService $uploadHandler, EventShowPageRenderService $eventShowPageRenderService)
    {
        $this->uploadHandler = $uploadHandler;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/list/{id}", name="app_featured_list", methods={"GET"})
     */
    public function list(
        Request $request,
        User $user,
        EventRepository $eventRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
        // Получаем Events
        $events = $eventRepository->createFeaturedEventsQueryBuilder($user)
            ->getQuery()
            ->getResult();

        // Получаем MatrixMaps
        $matrixMaps = [];
        $simpleRecords = [];
        $pictureRecords = [];

        // Объединяем и сортируем по дате создания
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        // Пагинация через ArrayAdapter
        $adapter = new \Pagerfanta\Adapter\ArrayAdapter($combined);
        $pagerfanta = new \Pagerfanta\Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        // Обогащаем только события
        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($e) {
                return $e instanceof Event;
            }),
            $this->getUser()
        );

        return $this->render('cpanel/events/featured/list.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"}, name="app_featured_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
