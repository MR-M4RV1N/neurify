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
use App\Repository\PictureRepository;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Repository\SimpleRepository;
use App\Service\ArrayFromItemsService;
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

/**
 * @Route("/cpanel/default/events")
 */
class DefaultReadController extends AbstractController
{
    private $uploadHandler;
    private $eventShowPageRenderService;
    public function __construct(ImageUploadHandlerService $uploadHandler, EventShowPageRenderService $eventShowPageRenderService)
    {
        $this->uploadHandler = $uploadHandler;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
    }

    /**
     * @Route("/list/{id}", name="app_default_list", methods={"GET"})
     */
    public function list(
        Request $request,
        User $user,
        PictureRepository $pictureRepository,
        SimpleRepository $simpleRepository,
        EventRepository $eventRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
        // Получаем Picture
        $pictureRecords = $pictureRepository->createDefaultPictureQueryBuilder($this->getUser());
        // Получаем Simple
        $simpleRecords = $simpleRepository->createDefaultSimpleQueryBuilder($this->getUser());
        // Получаем default-события
        $events = $eventRepository->createDefaultEventsQueryBuilder($this->getUser());
        // Получаем Matrix, не привязанные к ensemble (значит "default")
        $matrixMaps = $matrixMapRepository->createDefaultMatrixMapQueryBuilder($this->getUser());
        // Объединяем и сортируем по дате+время
        $combined = $eventService->mergeAndSortEventAndMatrix($pictureRecords, $simpleRecords, $events, $matrixMaps);

        // Создаем адаптер для Pagerfanta
        $adapter = new ArrayAdapter($combined);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        // Обогащаем события с помощью сервиса
        $data = $eventService->enrichEvents(
            array_filter($pagerfanta->getCurrentPageResults(), function ($item) {
                return $item instanceof \App\Entity\Event;
            }),
            $this->getUser()
        );

        return $this->render('cpanel/events/default/list.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"}, name="app_default_show", methods={"GET"})
     */
    public function show(Request $request, int $id): Response
    {
        return $this->eventShowPageRenderService->renderEventPage($request, $id, 'cpanel/events/_common/_show.html.twig');
    }
}
