<?php

namespace App\Controller\Cpanel;

use App\Entity\AiCareerConsultingResult;
use App\Entity\AiConsultingResult;
use App\Entity\Chat;
use App\Entity\Ensemble;
use App\Entity\EnsembleComplete;
use App\Entity\Event;
use App\Entity\EventRepeated;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\SmallStep;
use App\Entity\User;
use App\Form\EventType;
use App\Form\MessagesType;
use App\Form\SmallStepType;
use App\Repository\EventRepository;
use App\Repository\JournalWeekCommentRepository;
use App\Repository\LevelRepository;
use App\Repository\LikeRepository;
use App\Repository\SmallStepRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Journal\JournalCalendarService;
use App\Service\Journal\LifeBalanceAggregationService;
use App\Service\UserFilterService;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cpanel/proposal")
 */
class ProposalController extends AbstractController
{
    private $userFilterService;

    public function __construct(UserFilterService $userFilterService)
    {
        $this->userFilterService = $userFilterService;
    }

    /**
     * @Route("/list", name="app_proposal_list", methods={"GET"})
     */
    public function list(
        Request $request,
        Security $security,
        ManagerRegistry $doctrine,
        JournalCalendarService $journalCalendarService
    ): Response {
        // Получаем текущего пользователя
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Получаем страну и язык текущего пользователя
        $currentCountry = $currentUser->getCountry();
        $currentLang = $currentUser->getLang();

        // Разрешённые списки ID
        $allowedIds = []; // Инициализируем пустой массив
        $russianIds = []; // Для русских пользователей

        // Добавляем ID для Латвии
        if ($currentCountry === 'lv') {
            $allowedIds = [92, 127];
        }
        // Добавляем ID для пользователей с русским языком
        if ($currentLang === 'ru') {
            $allowedIds = array_merge($allowedIds, $russianIds);
        }

        // Используем сервис для получения QueryBuilder
        $queryBuilder = $this->userFilterService->getFilteredUsersByIds($allowedIds);

        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        $calendar = $journalCalendarService->buildCalendar(
            $currentUser,
            $request->query->get('month')
        );

        return $this->render('cpanel/proposal/list.html.twig', array_merge($calendar, [
            'users' => $pagerfanta,
            'platform' => $doctrine->getRepository(User::class)->find(102),
            'monthly' => $doctrine->getRepository(User::class)->find(126),
            'consultations' => count(
                $doctrine->getRepository(AiCareerConsultingResult::class)
                    ->findLastRecordsByUser($currentUser)
            ),
            'completed' => count(
                $doctrine->getRepository(EventRepeated::class)
                    ->findBy(['user' => $currentUser])
            ),
            'awards' => count(
                $doctrine->getRepository(EnsembleComplete::class)
                    ->findBy(['user' => $currentUser])
            ),
            'smallStepsDescription' => \App\Data\MethodologyData::getSmallStepsDescription($currentLang),
        ]));
    }

    /**
     * @Route("/collections", name="app_proposal_collections", methods={"GET"})
     */
    public function collections(
        Request $request,
        Security $security,
        ManagerRegistry $doctrine,
        JournalCalendarService $journalCalendarService
    ): Response {
        // Получаем текущего пользователя
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Получаем страну и язык текущего пользователя
        $currentCountry = $currentUser->getCountry();
        $currentLang = $currentUser->getLang();

        // Разрешённые списки ID
        $allowedIds = []; // Инициализируем пустой массив
        $russianIds = []; // Для русских пользователей

        // Добавляем ID для Латвии
        if ($currentCountry === 'lv') {
            $allowedIds = [92, 127];
        }
        // Добавляем ID для пользователей с русским языком
        if ($currentLang === 'ru') {
            $allowedIds = array_merge($allowedIds, $russianIds);
        }

        // Используем сервис для получения QueryBuilder
        $queryBuilder = $this->userFilterService->getFilteredUsersByIds($allowedIds);

        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/proposal/collections.html.twig', [
            'users' => $pagerfanta,
            'platform' => $doctrine->getRepository(User::class)->find(102),
            'monthly' => $doctrine->getRepository(User::class)->find(126)
        ]);
    }

    /**
     * @Route("/tasks_from_users", name="app_proposal_tasks_from_users", methods={"GET"})
     */
    public function tasksFromUsers(
        Request $request,
        Security $security,
        ManagerRegistry $doctrine,
        JournalCalendarService $journalCalendarService
    ): Response {
        // Получаем текущего пользователя
        /** @var User $currentUser */
        $currentUser = $security->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Получапем Ensemble у которых есть записи с меткой selected = true)
        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findBy(['type' => [3, 4]]);
        // Из Ensemble нужно взять записи, где user и selected = true
        $allowedIds = [];
        foreach ($users as $user) {
            $ensembles = $entityManager->getRepository(Ensemble::class)->findBy(['author' => $user, 'selected' => true]);
            if (count($ensembles) > 0) {
                $allowedIds[] = $user->getId();
            }
        }
        // Убрать [92, 127] из allowedIds если они там есть
        $allowedIds = array_diff($allowedIds, [92, 127, 102, 126]);
        // Используем сервис для получения QueryBuilder
        $queryBuilder = $this->userFilterService->getFilteredUsersByIds($allowedIds);

        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/proposal/collections_from_users.html.twig', [
            'users' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/week", name="app_proposal_week", methods={"GET","POST"})
     */
    public function week(
        Request $request,
        Security $security,
        JournalCalendarService $journalCalendarService,
        JournalWeekCommentRepository $weekCommentRepository,
        SmallStepRepository $repo,
        EventRepository $eventRepo
    ): Response {
        /** @var User|null $currentUser */
        $currentUser = $security->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // --------------------------
        // WEEK ANCHOR (YYYY-MM-DD)
        // --------------------------
        $weekParam = $request->query->get('week');
        $anchorDate = null;

        if ($weekParam) {
            try {
                $anchorDate = new \DateTimeImmutable($weekParam);
            } catch (\Exception $e) {
                // некорректный параметр — просто игнорируем
                $anchorDate = null;
            }
        }

        // --------------------------
        // BUILD WEEK (service)
        // --------------------------
        $currentWeek = $journalCalendarService->buildWeek(
            $currentUser,
            $anchorDate
        );
        $weekStart = $currentWeek['weekStart'];
        $weekComment = $weekCommentRepository->findForUserAndWeek(
            $currentUser,
            $weekStart
        );
        $weekTitle = $weekComment ? $weekComment->getTitle() : null;


        $year  = (int) $request->query->get('year', (int) date('Y'));
        $range = (string) $request->query->get('range', 'weeks');

        $weeks = [];
        $days  = [];

        if ($range === 'days') {
            $days = $repo->countByDayLast12ForUser($currentUser);
        } else {
            $weeks = $repo->countByIsoWeekForUserAndYear($currentUser, $year);
            $range = 'weeks';
        }

        $classification = $eventRepo->countEventsByPowerForUserForYear($this->getUser(), date('Y'));

        return $this->render(
            'cpanel/proposal/list.html.twig',
            array_merge($currentWeek, [
                'weekTitle' => $weekTitle,
                'profile'   => $currentUser,
                'year'      => $year,
                'range'     => $range,
                'weeks'     => $weeks,
                'days'      => $days,
                'classification' => $classification,
                'smallStepsDescription' => \App\Data\MethodologyData::getSmallStepsDescription($currentUser->getLang()),
            ])
        );
    }

    /**
     * @Route("/events/{id}", requirements={"id"="\d+"}, name="app_proposal_events", methods={"GET"})
     */
    public function events(EventRepository $eventRepository, $id): Response
    {
        return $this->render('cpanel/proposal/events.html.twig', [
            'events' => $eventRepository->findBy(['user' => $id, 'public' => true])
        ]);
    }

    /**
     * @Route("/events/show/{id}", name="app_proposal_show", methods={"GET"})
     */
    public function show(EventRepository $eventRepository, $id): Response
    {
        return $this->render('cpanel/proposal/show.html.twig', [
            'event' => $eventRepository->findOneBy(['id' => $id, 'public' => true])
        ]);
    }
}
