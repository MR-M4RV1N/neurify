<?php

namespace App\Controller\ThinkMaster;

use App\Entity\ThinkMaster\Note;
use App\Form\ThinkMaster\NoteType;
use App\Repository\ThinkMaster\NoteRepository;
use App\Service\ThinkMaster\NoteAdviceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ThinkMaster\NoteAnalysisService;

class NoteController extends AbstractController
{
    /**
     * @Route("/think-master/note/add", name="note_add", methods={"GET"})
     */
    public function add(): Response
    {
        $form = $this->createForm(NoteType::class);

        return $this->render('cpanel/think_master/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/think-master/note/create", name="note_create", methods={"POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        NoteAnalysisService $noteAnalysisService,
        NoteAdviceService $noteAdviceService
    ): Response {
        $note = new Note();
        $note->setCreatedAt(new \DateTimeImmutable());
        $note->setUpdatedAt(new \DateTimeImmutable());
        $note->setUser($this->getUser());

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $noteAnalysisService->generateAnalysis($note);
            $noteAdviceService->generateAdvice($note);

            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('note_show', [
                'id' => $note->getId()
            ]);
        }

        return $this->render('cpanel/think_master/note/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/think-master/note/{id}", name="note_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(Note $note): Response
    {
        return $this->render('cpanel/think_master/show.html.twig', [
            'note' => $note
        ]);
    }
}