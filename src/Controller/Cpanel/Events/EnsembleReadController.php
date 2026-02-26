<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\EnsembleParticipant;
use App\Entity\Event;
use App\Entity\Ensemble;
use App\Entity\User;
use App\Form\EnsembleType;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Repository\LikeRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Service\EnsembleParticipantService;
use App\Service\EventService;
use App\Service\EventShowPageRenderService;
use App\Service\ImageUploadHandlerService;
use App\Service\PaginatorService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cpanel/ensemble")
 */
class EnsembleReadController extends AbstractController
{
    private $participantService;
    private $paginatorService;
    private $imageUploadHandler;
    private $eventShowPageRenderService;
    public function __construct(
        EnsembleParticipantService $participantService,
        PaginatorService $paginatorService,
        ImageUploadHandlerService $imageUploadHandler,
        EventShowPageRenderService $eventShowPageRenderService
    ) {
        $this->participantService = $participantService;
        $this->paginatorService = $paginatorService;
        $this->imageUploadHandler = $imageUploadHandler;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/open/{id}", requirements={"id"="\d+"}, name="app_ensemble_open", methods={"GET"})
     */
    public function open(
        Request $request,
        Ensemble $ensemble,
        EnsembleParticipantRepository $ensembleParticipantRepository,
        PictureRepository $pictureRepository,
        SimpleRepository $simpleRepository,
        EventRepository $eventRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
        // Получаем Picture
        $pictureRecords = $pictureRepository->createGroupPictureQueryBuilder($ensemble);
        // Получаем события группы
        $simpleRecords = $simpleRepository->createGroupSimpleQueryBuilder($ensemble);
        // Получаем события группы
        $events = $eventRepository->createGroupEventsQueryBuilder($ensemble);
        // Получаем матрицы этой группы
        $matrixMaps = $matrixMapRepository->createGroupMatrixMapQueryBuilder($ensemble);
        // Объединяем и сортируем по дате+время
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        // Пагинация
        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $data = $eventService->enrichEvents(
            array_filter(
                $pagerfanta->getCurrentPageResults(),
                function ($e) {
                    return $e instanceof Event;
                }
            ),
            $this->getUser()
        );

        return $this->render('cpanel/events/ensemble/open.html.twig', [
            'ensemble' => $ensemble,
            'participants' => $ensembleParticipantRepository->findBy(['ensemble' => $ensemble->getId()]),
            'checkParticipant' => $ensembleParticipantRepository->findOneBy([
                'ensemble' => $ensemble->getId(),
                'user' => $this->getUser()
            ]),
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"}, name="app_ensemble_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
