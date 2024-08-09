<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\Ensemble;
use App\Entity\EnsembleParticipant;
use App\Entity\User;
use App\Form\EnsembleType;
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
 * @Route("/cpanel/ensemble")
 */
class EnsembleController extends AbstractController
{
    /**
     * @Route("/list", name="app_ensemble_list", methods={"GET"})
     */
    public function list(Request $request,EnsembleRepository $ensembleRepository): Response
    {
        $queryBuilder = $ensembleRepository->createEnsamblesQueryBuilder($this->getUser());
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/ensemble/list.html.twig', [
            'ensembles' => $pagerfanta,
            'title' => 'All communities'
        ]);
    }

    /**
     * @Route("/new", name="app_ensemble_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security, SluggerInterface $slugger): Response
    {
        $entityManager = $doctrine->getManager();
        $ensemble = new Ensemble();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }
        $ensemble->setAuthor($user);
        $ensemble->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(EnsembleType::class, $ensemble, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                $entityManager->persist($ensemble);

                // Проверка нет ли уже участника
                $participant = $entityManager->getRepository(EnsembleParticipant::class)->findOneBy(['ensemble' => $ensemble->getId(), 'user' => $user]);
                if ($participant) {
                    $this->addFlash('danger', 'Вы уже являетесь участником этого ансамбля.');
                    return $this->redirectToRoute('app_ensemble_list', [], Response::HTTP_SEE_OTHER);
                }
                // Создаём участника в таблице EnsembleParticipant
                $participant = new EnsembleParticipant();
                $participant->setEnsemble($ensemble);
                $participant->setUser($user);
                $entityManager->persist($participant);

                $entityManager->flush();
                return $this->redirectToRoute('app_ensemble_list', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('cpanel/ensemble/new.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="app_ensemble_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ensemble $ensemble, EnsembleRepository $ensembleRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EnsembleType::class, $ensemble, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ensembleRepository->add($ensemble, true);
            return $this->redirectToRoute('app_ensemble_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/ensemble/edit.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_ensemble_delete", methods={"POST"})
     */
    public function delete(ManagerRegistry $doctrine, Security $security, Request $request, Ensemble $ensemble, EnsembleRepository $ensembleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ensemble->getId(), $request->request->get('_token'))) {
            $ensembleRepository->remove($ensemble, true);
        }

        return $this->redirectToRoute('app_ensemble_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/open/{id}", requirements={"id"="\d+"}, name="app_ensemble_open", methods={"GET"})
     */
    public function open(Request $request, Ensemble $ensemble, EnsembleParticipantRepository $ensembleParticipantRepository, EventRepository $eventRepository): Response
    {
        $queryBuilder = $eventRepository->createGroupEventsQueryBuilder(true, 2);
        // Создаем адаптер для Pagerfanta
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(12); // Количество элементов на странице
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1)); // Текущая страница

        return $this->render('cpanel/ensemble/open.html.twig', [
            'ensemble' => $ensemble,
            'participants' => $ensembleParticipantRepository->findBy(['ensemble' => $ensemble->getId()]),
            'checkParticipant' => $ensembleParticipantRepository->findOneBy(['ensemble' => $ensemble->getId(), 'user' => $this->getUser()]),
            'events' => $pagerfanta
        ]);
    }

    /**
     * @Route("/join/{id}", requirements={"id"="\d+"}, name="app_ensemble_join", methods={"POST"})
     */
    public function join(Request $request, Ensemble $ensemble, ManagerRegistry $doctrine, Security $security): Response
    {
        $entityManager = $doctrine->getManager();
        $item = new EnsembleParticipant();
        $item->setEnsemble($ensemble);
        $item->setUser($security->getUser());
        $entityManager->persist($item);
        $entityManager->flush();

        return $this->redirectToRoute('app_ensemble_open', ['id' => $ensemble->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/leave/{id}", requirements={"id"="\d+"}, name="app_ensemble_leave", methods={"POST"})
    */
    public function leave(Request $request, Ensemble $ensemble, ManagerRegistry $doctrine, Security $security): Response
    {
        $entityManager = $doctrine->getManager();
        $item = $doctrine->getRepository(EnsembleParticipant::class)->findOneBy(['ensemble' => $ensemble->getId(), 'user' => $security->getUser()]);
        $entityManager->remove($item);

        $events = $entityManager->getRepository(Event::class)->findBy(['ensemble' => $ensemble->getId()]);
        foreach ($events as $event) {
            $event->setEnsemble(null);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_ensemble_open', ['id' => $ensemble->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/user/{id}/{ensemble}", requirements={"id"="\d+"}, name="app_ensemble_user")
     */
    public function user(User $user, Ensemble $ensemble, ManagerRegistry $doctrine, Request $request): Response
    {
        return $this->renderForm('cpanel/ensemble/user.html.twig', [
            'profile' => $user,
            'events' => $doctrine->getRepository(Event::class)->findBy(
                ['user' => $user, 'public' => true, 'ensemble' => $ensemble],
                ['id' => 'DESC']
            ),
            'ensemble' => $ensemble->getTitle(),
        ]);
    }
}
