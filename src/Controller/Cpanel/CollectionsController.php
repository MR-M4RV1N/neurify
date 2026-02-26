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
use App\Entity\User;
use App\Form\EventType;
use App\Form\MessagesType;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Repository\JournalWeekCommentRepository;
use App\Repository\LevelRepository;
use App\Repository\LikeRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\EnsembleProgressService;
use App\Service\Journal\JournalCalendarService;
use App\Service\StepwiseService;
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
 * @Route("/cpanel/collections")
 */
class CollectionsController extends AbstractController
{
    private $userFilterService;

    public function __construct(UserFilterService $userFilterService)
    {
        $this->userFilterService = $userFilterService;
    }

    /**
     * @Route("/platform", name="collections_platform", methods={"GET"})
     */
    public function collections(
        Request                $request,
        Security               $security,
        ManagerRegistry        $doctrine,
        JournalCalendarService $journalCalendarService
    ): Response
    {
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
        if ($currentCountry == 'LV' || $currentLang == 'lv') {
            $allowedIds = [92, 127, 102, 126];
        } else {
            $allowedIds = [];
        }

        if ($this->getParameter('kernel.environment') === 'dev') {
            $allowedEnsembles = [104, 101, 78];
        } else {
            $allowedEnsembles = [104, 78, 112, 105, 103];
        }

        // Используем сервис для получения QueryBuilder
        $queryBuilder = $this->userFilterService->getFilteredUsersByIds($allowedIds);

        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        $ensembles = $doctrine->getRepository(Ensemble::class)->findBy(['id' => $allowedEnsembles], ['id' => 'ASC']);

        return $this->render('cpanel/collections/platform.html.twig', [
            'users' => $pagerfanta,
            'platform' => $doctrine->getRepository(User::class)->find(102),
            'monthly' => $doctrine->getRepository(User::class)->find(126),
            'ensembles' => $ensembles,
        ]);
    }

    /**
     * @Route("/my", name="collections_my", methods={"GET"})
     */
    public function my(
        EnsembleRepository $ensembleRepository
    ): Response
    {
        $selectedEnsembles = $ensembleRepository->findBy(['author' => $this->getUser(), 'selected' => 1, 'aiGenerated' => false]);

        return $this->render('cpanel/collections/my.html.twig', [
            'ensembles' => $selectedEnsembles,
            'profile' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/ai", name="collections_ai", methods={"GET"})
     */
    public function ai(
        EnsembleRepository $ensembleRepository
    ): Response
    {
        $generatedEnsembles = $ensembleRepository->findBy(['author' => $this->getUser(), 'aiGenerated' => true], ['created_at' => 'DESC']);

        return $this->render('cpanel/collections/ai.html.twig', [
            'ensembles' => $generatedEnsembles,
            'profile' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/open-ensemble/{id}", name="collections_open_ensemble", methods={"GET"})
     */
    public function openEnsemble($id, ManagerRegistry $doctrine, StepwiseService $stepwise, EnsembleProgressService $progress): Response
    {
        $ensemble = $doctrine->getRepository(Ensemble::class)->find($id);

        // проверяем прогресс
        $completed = $progress->checkProgress($this->getUser(), $this->getUser(), $ensemble ?? null);
        // если всё выполнено и шаблон ещё НЕ создан → делаем запись и редирект
        if ($completed === 1) {
            return $this->redirectToRoute('ensemble_complete_congratulations');
        }

        $data = $stepwise->buildForEnsemble($ensemble, $this->getUser());

        $profile = $doctrine->getRepository(User::class)->find(102);
        $profile->setDescription('Ņēmot vērā jūsu profila aprakstu un izaicinājumus, šeit ir AI ģenerēts uzdevumu saraksts, kas pielāgots jūsu vajadzībām un mērķiem.');
        $profile->setImage('nf-ai.png');
        $profile->setArtisan('Individuālo izaicinājumu konsultants');
        $profile->setFirstname('NEURIFY');
        $profile->setLastname('MASTER');
        $data['profile'] = $profile;
        $data['level'] = 20;
        $data['ensemble'] = $ensemble;

        return $this->render('/cpanel/collections/list.html.twig', $data);
    }
}
