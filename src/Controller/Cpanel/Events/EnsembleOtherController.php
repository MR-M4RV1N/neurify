<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Event;
use App\Entity\Ensemble;
use App\Entity\EnsembleParticipant;
use App\Entity\User;
use App\Form\EnsembleType;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Service\EnsembleProgressService;
use App\Service\ImageUploadHandlerService;
use App\Service\StepwiseService;
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
    private $uploadHandler;
    public function __construct(ImageUploadHandlerService $uploadHandler)
    {
        $this->uploadHandler = $uploadHandler;
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

        return $this->render('cpanel/events/ensemble/list.html.twig', [
            'ensembles' => $pagerfanta,
            'title' => 'My communities'
        ]);
    }


    /**
     * @Route("/join/{id}", requirements={"id"="\d+"}, name="app_ensemble_join", methods={"POST"})
     */
    public function join(Ensemble $ensemble, Security $security): Response
    {
        $user = $security->getUser();
        if ($this->participantService->addParticipant($ensemble, $user)) {
            $this->addFlash('success', 'Вы успешно присоединились к ансамблю.');
        } else {
            $this->addFlash('danger', 'Вы уже являетесь участником этого ансамбля.');
        }

        return $this->redirectToRoute('app_ensemble_open', ['id' => $ensemble->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/leave/{id}", requirements={"id"="\d+"}, name="app_ensemble_leave", methods={"POST"})
     */
    public function leave(Ensemble $ensemble, Security $security, ManagerRegistry $doctrine): Response
    {
        $user = $security->getUser();
        $this->participantService->removeParticipant($ensemble, $user);

        $entityManager = $doctrine->getManager();
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
    public function user(User $user, Ensemble $ensemble, ManagerRegistry $doctrine): Response
    {
        return $this->renderForm('cpanel/events/ensemble/user.html.twig', [
            'profile' => $user,
            'events' => $doctrine->getRepository(Event::class)->findBy(
                ['user' => $user, 'public' => true, 'ensemble' => $ensemble],
                ['id' => 'DESC']
            ),
            'ensemble' => $ensemble->getTitle(),
        ]);
    }

    /**
     * @Route("/stepwised/{ensemble}", requirements={"ensemble"="\d+"}, name="ensemble_stepwised", methods={"GET"})
     */
    public function stepwiseEnsembleOpen(Ensemble $ensemble, StepwiseService $stepwise, EnsembleProgressService $progress): Response
    {
        // проверяем прогресс
        $completed = $progress->checkProgress($ensemble->getAuthor(), $this->getUser(), $ensemble ?? null);
        // если всё выполнено и шаблон ещё НЕ создан → делаем запись и редирект
        if ($completed === 1) {
            return $this->redirectToRoute('ensemble_complete_congratulations');
        }
        $data = $stepwise->buildForEnsemble($ensemble, $this->getUser());
        return $this->render('/cpanel/events/user/stepwise/list.html.twig', $data);
    }
}
