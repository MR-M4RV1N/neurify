<?php

namespace App\Controller\Cpanel;

use App\Consulting\Model\Mbti\MbtiPromptProvider;
use App\Entity\AiCareerConsultingResult;
use App\Entity\AiConsultingResult;
use App\Entity\Bookmark;
use App\Entity\BookmarkRegister;
use App\Entity\Ensemble;
use App\Entity\EnsembleComplete;
use App\Entity\Image;
use App\Entity\Progress;
use App\Entity\Event;
use App\Entity\Level;
use App\Entity\Swot;
use App\Entity\User;
use App\Entity\WeekChallenge;
use App\Repository\EventRepeatedRepository;
use App\Repository\EventRepository;
use App\Repository\MatrixMapRepository;
use App\Repository\PictureRepository;
use App\Repository\SimpleRepository;
use App\Service\EventService;
use App\Service\UserWeekChallengeService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\ArrayAdapter;

class DashboardController extends AbstractController
{
    /**
     * @Route("/cpanel/dashboard", name="app_cpanel_dashboard")
     */
    public function index(ManagerRegistry $doctrine, UserWeekChallengeService $service): Response
    {
        $entityManager = $doctrine->getManager();

        // Получаем последнюю запись из WeekChallenge
        $weekChallenge = $doctrine->getRepository(WeekChallenge::class)->findOneBy([], ['id' => 'DESC']);

        // Если вызова недели нет или он не соответствует текущему году/неделе — создаём новый
        if (!$weekChallenge || $weekChallenge->getYear() != date("Y") || $weekChallenge->getWeek() != date("W")) {
            // Берём user с id = 65 и получаем все его события
            $allEvents = $doctrine->getRepository(Event::class)->findBy(['user' => 65]);

            // Получаем последние 4 вызова
            $lastWeekChallenges = $doctrine->getRepository(WeekChallenge::class)->findBy([], ['id' => 'DESC'], 4);

            // Фильтруем события, исключая те, что уже были вызовом недели
            $filteredAllEvents = array_filter($allEvents, function ($event) use ($lastWeekChallenges) {
                foreach ($lastWeekChallenges as $lastWeekChallenge) {
                    $lastEvent = $lastWeekChallenge->getEvent();
                    if ($lastEvent && $event->getId() == $lastEvent->getId()) {
                        return false;
                    }
                }
                return true;
            });

            if (empty($filteredAllEvents)) {
                return $this->render('cpanel/dashboard/index.html.twig', [
                    'weekChallenge' => null,
                    'weekImage' => null,
                    'isAccepted' => false,
                    'allParticipants' => [],
                ]);
            }

            // Выбираем случайное событие
            $selectedEvent = $filteredAllEvents[array_rand($filteredAllEvents)];

            // Создаём новую запись WeekChallenge
            $newWeekChallenge = new WeekChallenge();
            $newWeekChallenge->setEvent($selectedEvent);
            $newWeekChallenge->setYear(date("Y"));
            $newWeekChallenge->setWeek(date("W"));

            $entityManager->persist($newWeekChallenge);
            $entityManager->flush();

            $weekChallenge = $newWeekChallenge;
        }

        // Защита от `null` в `weekChallenge->getEvent()`
        $event = $weekChallenge ? $weekChallenge->getEvent() : null;
        $eventId = $event ? $event->getId() : null;

        if (!$eventId) {
            return $this->render('cpanel/dashboard/index.html.twig', [
                'weekChallenge' => null,
                'weekImage' => null,
                'isAccepted' => false,
                'allParticipants' => [],
            ]);
        }

        // **🔥 Оптимизированный запрос для получения участников с `completed`**
        $usersWithStatus = $service->getUsersWithChallengeStatus($eventId);

        // Проверяем, принял ли текущий пользователь вызов
        $isAccepted = (bool) $doctrine->getRepository(Bookmark::class)->findOneBy([
            'user' => $this->getUser(),
            'event' => $eventId
        ]);
        // Проверяем выполнил ли пользователь вызов недели
        $isCompleted = (bool) $doctrine->getRepository(BookmarkRegister::class)->findOneBy([
            'user' => $this->getUser(),
            'week_challenge' => $weekChallenge,
        ]);

        // Получаем изображение для текущего вызова
        $weekImage = $doctrine->getRepository(Image::class)->findOneBy(['event' => $eventId]) ?: null;

        return $this->render('cpanel/dashboard/index.html.twig', [
            'weekChallenge' => $weekChallenge,
            'weekImage' => $weekImage,
            'isAccepted' => $isAccepted,
            'isCompleted' => $isCompleted,
            'allParticipants' => $usersWithStatus,
        ]);
    }

    /**
     * @Route("/cpanel/dashboard/consultations", name="dashboard_consultations")
     */
    public function consultations(ManagerRegistry $doctrine, UserWeekChallengeService $service, MbtiPromptProvider $mbtiPromptProvider): Response
    {
        $user = $this->getUser();
        $lang = $user->getLang();

        $mbtiStyles = ['Se', 'Si', 'Ne', 'Ni', 'Te', 'Ti', 'Fe', 'Fi'];
        $mbtiStyleDescriptions = [];
        $mbtiStyleLabels = [];
        foreach ($mbtiStyles as $style) {
            $mbtiStyleDescriptions[$style] = $mbtiPromptProvider->getStyleDescription($style, $lang);
            $mbtiStyleLabels[$style] = $mbtiPromptProvider->getStyleShortLabel($style, $lang);
        }

        return $this->render('cpanel/dashboard/consultations.html.twig', [
            'lastConsulting' => $this->getDoctrine()->getRepository(AiCareerConsultingResult::class)->findLastRecordsByUser($this->getUser()),
            //'checkMbti' => $this->getDoctrine()->getRepository(MbtiResult::class)->count(['user' => $this->getUser()]) > 0,
            'checkSwot' => $this->getDoctrine()->getRepository(Swot::class)->count(['user' => $this->getUser()]) > 0,
            'checkProfileDescription' => !empty($this->getUser()->getDescription()),
            //'checkResourceMap' => $this->getDoctrine()->getRepository(ResourceMap::class)->count(['user' => $this->getUser()]) > 0,
            'checkLastMonthEvents' => $this->getDoctrine()->getRepository(Event::class)->countLastMonthEventsByUser($this->getUser()) > 0,
            'mbtiStyleDescriptions' => $mbtiStyleDescriptions,
            'mbtiStyleLabels' => $mbtiStyleLabels,
        ]);
    }

    /**
     * @Route("/cpanel/dashboard/completed-paths", name="dashboard_completed_paths")
     */
    public function completedPaths(): Response
    {
        $completedCategories = $this->getDoctrine()->getRepository(EnsembleComplete::class)->findBy(['user' => $this->getUser()]);
        // Перечисляем $completedCategories и получаем соответствующие Ensemble
        $completedEnsembles = [];
        foreach ($completedCategories as $completed) {
            $ensemble = $completed->getEnsemble();
            if ($ensemble) {
                $completedEnsembles[] = $ensemble;
            }
        }
        return $this->render('cpanel/dashboard/completed_paths.html.twig', [
            'completedEnsembles' => $completedEnsembles,
        ]);
    }

    /**
     * @Route("/cpanel/dashboard/compteted-tasks", name="dashboard_completed_tasks")
     */
    public function completedTasks(
        Request $request,
        PictureRepository $pictureRepository,
        SimpleRepository $simpleRepository,
        EventRepository $eventRepository,
        EventRepeatedRepository $eventRepeatedRepository,
        MatrixMapRepository $matrixMapRepository,
        EventService $eventService
    ): Response {
        // Получаем default-события
        $eventRepeated = $eventRepeatedRepository->findBy(['user' => $this->getUser()]);
        // В Event найти записи, которые совпадают с $eventRepeated по полю replyEventId
        $events = [];
        foreach ($eventRepeated as $repeated) {
            $foundEvents = $eventRepository->findBy(['id' => $repeated->getReplyEvent(), 'user' => $this->getUser()]);
            $events = array_merge($events, $foundEvents);
        }
        // $events = $eventRepository->findBy(['user' => $this->getUser(),'aiGenerated' => true,'aiCompleted' => true]);
        // Создаем адаптер для Pagerfanta
        $adapter = new ArrayAdapter($events);
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

        return $this->render('cpanel/dashboard/completed_tasks.html.twig', [
            'events' => $pagerfanta->getCurrentPageResults(),
            'likedEvents' => $data['likedEvents'],
            'bookmarkedEvents' => $data['bookmarkedEvents'],
            'pager' => $pagerfanta,
        ]);
    }
}
