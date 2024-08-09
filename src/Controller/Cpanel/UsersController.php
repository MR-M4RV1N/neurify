<?php

namespace App\Controller\Cpanel;

use App\Entity\Chat;
use App\Entity\Event;
use App\Entity\Messages;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\EventType;
use App\Form\MessagesType;
use App\Repository\EventRepository;
use App\Repository\LevelRepository;
use App\Repository\UserRepository;
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
 * @Route("/cpanel/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/list", name="app_users_list", methods={"GET"})
     */
    public function list(Request $request, UserRepository $userRepository, LevelRepository $levelRepository): Response
    {
        $queryBuilder = $userRepository->createUsersQueryBuilder();
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/users/list.html.twig', [
            'users' => $pagerfanta
        ]);
    }

    /**
     * @Route("/events/{id}", requirements={"id"="\d+"}, name="app_users_events", methods={"GET"})
     */
    public function events(EventRepository $eventRepository, $id): Response
    {
        return $this->render('cpanel/users/events.html.twig', [
            'events' => $eventRepository->findBy(['user' => $id, 'public' => true])
        ]);
    }

    /**
     * @Route("/events/show/{id}", name="app_users_events_show", methods={"GET"})
     */
    public function show(EventRepository $eventRepository, $id): Response
    {
        return $this->render('cpanel/users/show.html.twig', [
            'event' => $eventRepository->findOneBy(['id' => $id, 'public' => true])
        ]);
    }

    /**
     * @Route("/user/{id}", name="app_users_user")
     */
    public function user(User $user, ManagerRegistry $doctrine, LevelRepository $levelRepository): Response
    {
        $level = ceil($user->getProgress() / 10);

        return $this->renderForm('cpanel/users/user.html.twig', [
            'profile' => $user,
            'events' => $doctrine->getRepository(Event::class)->findBy(
                ['user' => $user, 'public' => true],
                ['id' => 'DESC']
            ),
            'level' => $levelRepository->findOneBy(['lang' => $user->getLang(), 'number' => $level == 0 ? 1 : $level]),
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
}
