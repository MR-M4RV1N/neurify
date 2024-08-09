<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\Ensemble;
use App\Entity\EnsembleParticipant;
use App\Entity\User;
use App\Form\EnsembleType;
use App\Repository\CommentRepository;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/cpanel/ensemble/other/")
 */
class EnsembleOtherController extends AbstractController
{
    /**
     * @Route("show/{id}", requirements={"id"="\d+"}, name="app_ensemble_event_show", methods={"GET"})
     */
    public function show(Request $request, EventRepository $eventRepository, CommentRepository $commentRepository, $id): Response
    {
        // Проверяем, что событие публичное
        $event = $eventRepository->findOneBy(['id' => $id, 'public' => true]);
        if (!$event) {
            throw $this->createNotFoundException('The event does not exist');
        }

        $queryBuilder = $commentRepository->createCommentsQueryBuilder($event->getId());
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/ensemble/other/show.html.twig', [
            'event' => $event,
            'comments' => $pagerfanta
        ]);
    }

    /**
     * @Route("closed", name="app_ensemble_other_closed", methods={"GET"})
     */
    public function closed(Request $request,EnsembleRepository $ensembleRepository): Response
    {
        $queryBuilder = $ensembleRepository->createEnsamblesQueryBuilderClosed($this->getUser());
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/ensemble/list.html.twig', [
            'ensembles' => $pagerfanta,
            'title' => 'My communities'
        ]);
    }
}
