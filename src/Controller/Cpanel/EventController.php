<?php

namespace App\Controller\Cpanel;

use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Wording;
use App\Form\EventType;
use App\Repository\ProgressRepository;
use App\Repository\EventRepository;
use App\Service\ArrayFromItemsService;
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

class EventController extends AbstractController
{
    private $imageRemovalService;
    private $uploadHandler;
    public function __construct(ImageRemovalService $imageRemovalService, ImageUploadHandlerService $uploadHandler)
    {
        $this->imageRemovalService = $imageRemovalService;
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @Route("/cpanel/progress/events/list", name="app_events_list", methods={"GET"})
     */
    public function list(Request $request, EventRepository $eventRepository): Response
    {
        $queryBuilder = $eventRepository->createEventsQueryBuilder($this->getUser());
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/event/index.html.twig', [
            'events' => $pagerfanta
        ]);
    }

    /**
     * @Route("/cpanel/editor/events/last", name="app_events_last", methods={"GET"})
     */
    public function last(Request $request, EventRepository $eventRepository): Response
    {
        // Выясняем сколько записей в $eventRepository у пользователя
        $count = $eventRepository->count(['user' => $this->getUser()]);
        // Узнаём остаток от деления
        $remainder = $count % 10;
        // Выбираем из $eventRepository последние $remainder записей
        $queryBuilder = $eventRepository->createEventsRemainderQueryBuilder($this->getUser(), $remainder);

        return $this->render('cpanel/event/last.html.twig', [
            'events' => $queryBuilder->getQuery()->getResult()
        ]);
    }

    /**
     * @Route("/cpanel/editor/events/new", name="app_event_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security, SluggerInterface $slugger): Response
    {
        $entityManager = $doctrine->getManager();
        $event = new Event();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }
        $event->setUser($user);
        $event->setPublic(false);
        $event->setLang($user->getLang());

        $form = $this->createForm(EventType::class, $event, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Устанавливаем директорию для загрузки изображений
            $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_events'));
            // Обработка изображения
            $imageName = $this->uploadHandler->handleUpload($form, 'image');
            // Устанавливаем имя файла изображения
            $event->setImage($imageName);

            $entityManager->persist($event);

            // Обновляем ячейку progress в таблице User
            $item = $entityManager->getRepository(User::class)->find($user);
            $progress = $item->getProgress() + 1;
            $item->setProgress($progress);
            $entityManager->persist($item);

            $entityManager->flush();

            return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/editor/events/{id}/edit", requirements={"id"="\d+"}, name="app_event_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Event $event, EventRepository $eventRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EventType::class, $event, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Проверяем загружен ли файл изображения
            if ($form->get('image')->getData() instanceof UploadedFile) {
                // Удаляем старый файл изображения
                $this->imageRemovalService->removeImage($event->getImage(), $this->getParameter('image_upload_directory_events'));
                // Устанавливаем директорию для загрузки изображений
                $this->uploadHandler->setUploadDirectory($this->getParameter('image_upload_directory_events'));
                // Использование uploadHandler для обработки загруженного файла
                $newFilename = $this->uploadHandler->handleUpload($form, 'image');
                // Устанавливаем новое имя файла изображения
                $event->setImage($newFilename);
            }

            $eventRepository->add($event, true);

            return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/cpanel/editor/events/{id}", requirements={"id"="\d+"}, name="app_event_delete", methods={"POST"})
     */
    public function delete(ManagerRegistry $doctrine, Security $security, Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            // Удаляем старый файл изображения
            $this->imageRemovalService->removeImage($event->getImage(), $this->getParameter('image_upload_directory_events'));
            // Удаляем сущность
            $eventRepository->remove($event, true);

            // Обновляем ячейку progress в таблице User
            $entityManager = $doctrine->getManager();
            $item = $entityManager->getRepository(User::class)->find($security->getUser());
            $progress = $item->getProgress() - 1;
            $item->setProgress($progress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cpanel/editor/events/{id}", requirements={"id"="\d+"}, name="app_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('cpanel/event/show.html.twig', [
            'event' => $event,
        ]);
    }
}
