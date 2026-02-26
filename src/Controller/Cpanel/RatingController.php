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
 * @Route("/cpanel/rating")
 */
class RatingController extends AbstractController
{
    /**
     * @Route("/list", name="app_rating_list", methods={"GET"})
     */
    public function list(Request $request, UserRepository $userRepository): Response
    {
        $queryBuilder = $userRepository->createUsersByRatingQueryBuilder();
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $currentPage = $request->query->getInt('page', 1); // Текущая страница
        $pagerfanta->setCurrentPage($currentPage);

        return $this->render('cpanel/rating/list.html.twig', [
            'users' => $pagerfanta,
            'currentPage' => $currentPage,
            'itemsPerPage' => 12,
        ]);
    }
}
