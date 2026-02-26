<?php

namespace App\Controller\Cpanel;

use App\Entity\Chat;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\EventType;
use App\Form\MessagesType;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Repository\LevelRepository;
use App\Repository\LikeRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\EventShowPageRenderService;
use App\Service\Journal\JournalCalendarService;
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
use App\Service\PaginatorService;

/**
 * @Route("/cpanel/users")
 */
class UsersController extends AbstractController
{
    private $paginatorService;
    private $eventShowPageRenderService;
    private $userFilterService;

    public function __construct(
        PaginatorService $paginatorService,
        EventShowPageRenderService $eventShowPageRenderService,
        UserFilterService $userFilterService
    ) {
        $this->paginatorService = $paginatorService;
        $this->eventShowPageRenderService = $eventShowPageRenderService;
        $this->userFilterService = $userFilterService;
    }

    /**
     * @Route("/all", name="app_users_all", methods={"GET"})
     */
    public function all(Request $request, UserRepository $userRepository, LevelRepository $levelRepository): Response
    {
        $queryBuilder = $userRepository->createUsersQueryBuilder();
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/users/all.html.twig', [
            'users' => $pagerfanta
        ]);
    }

    /**
     * @Route("/username/{username}", name="app_users_username")
     */
    public function byUsername(ManagerRegistry $doctrine, LevelRepository $levelRepository, $username): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['username' => $username]);
        // Проверяем, что пользователь существует
        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $level = ceil($user->getProgress() / 10);

        return $this->renderForm('cpanel/users/username.html.twig', [
            'profile' => $user,
            'events' => $doctrine->getRepository(Event::class)->findBy(
                ['user' => $user, 'public' => true],
                ['id' => 'DESC']
            ),
            'level' => $levelRepository->findOneBy(['lang' => $user->getLang(), 'number' => $level == 0 ? 1 : $level]),
        ]);
    }

    /**
     * @Route("/following", name="user_following", methods={"GET"})
     */
    public function following(Request $request, Security $security, UserRepository $userRepository): Response
    {
        $currentUser = $security->getUser();

        if (!$currentUser) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы.');
        }

        // Получаем QueryBuilder для пользователей, на которых подписан текущий пользователь
        $queryBuilder = $userRepository->createFollowedUsersQueryBuilder($currentUser->getId());

        // Создаём адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);

        // Устанавливаем количество пользователей на страницу
        $pagerfanta->setMaxPerPage(10);

        // Устанавливаем текущую страницу
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        return $this->render('/cpanel/users/following.html.twig', [
            'users' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/search", name="app_user_search", methods={"GET"})
     */
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q', '');

        $users = [];
        if (!empty($query)) {
            $users = $userRepository->findByUsernameLike($query);
        }

        return $this->render('/cpanel/users/search.html.twig', [
            'users' => $users,
            'query' => $query,
        ]);
    }

    /**
     * @Route("/collections", name="collections_users", methods={"GET"})
     */
    public function collections(
        EnsembleRepository $ensembleRepository
    ): Response
    {
        $excludedIds = [104, 78, 112, 105, 103];

        $selectedEnsembles = $ensembleRepository->createQueryBuilder('e')
            ->where('e.selected = :selected')
            ->andWhere('e.aiGenerated = :ai')
            ->andWhere('e.id NOT IN (:excluded)')
            ->setParameter('selected', 1)
            ->setParameter('ai', false)
            ->setParameter('excluded', $excludedIds)
            ->getQuery()
            ->getResult();

        return $this->render('cpanel/users/collections.html.twig', [
            'ensembles' => $selectedEnsembles,
        ]);
    }
}