<?php

namespace App\Controller\Cpanel;

use App\Entity\Swot;
use App\Repository\SwotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwotController extends AbstractController
{
    /**
     * @Route("/ai_consulting/swot", name="app_swot_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SwotRepository $swotRepo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in.');
        }

        // 1) Найти существующую запись или создать новую
        $swot = $swotRepo->findOneBy(['user' => $user]);
        $isNew = false;
        if (!$swot) {
            $swot = new Swot();
            $swot->setUser($user);
            $now = new \DateTimeImmutable();
            $swot->setCreatedAt($now);
            $swot->setUpdatedAt($now);
            $isNew = true;
        }

        // 2) Форма (простая, без отдельного FormType)
        $form = $this->createFormBuilder($swot)
            ->add('strengths', TextareaType::class, [
                'label' => 'Strengths',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Ваши сильные стороны'],
            ])
            ->add('weaknesses', TextareaType::class, [
                'label' => 'Weaknesses',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Ваши слабые стороны'],
            ])
            ->add('opportunities', TextareaType::class, [
                'label' => 'Opportunities',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Возможности'],
            ])
            ->add('threats', TextareaType::class, [
                'label' => 'Threats',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Угрозы'],
            ])
            ->add('save', SubmitType::class, [
                'label' => $isNew ? 'Create SWOT' : 'Save SWOT',
                'attr'  => ['class' => 'btn btn-primary mt-3'],
            ])
            ->getForm();

        $form->handleRequest($request);

        // 3) Обработка
        if ($form->isSubmitted() && $form->isValid()) {
            // createdAt уже выставлен при создании; updatedAt — всегда сейчас
            $swot->setUpdatedAt(new \DateTimeImmutable());

            // persist безопасно и для новых, и для существующих
            $em->persist($swot);
            $em->flush();

            $this->addFlash('success', 'SWOT saved.');
            return $this->redirectToRoute('ai_consulting');
        }

        return $this->render('cpanel/swot/swot_edit.html.twig', [
            'form' => $form->createView(),
            'swot' => $swot,
        ]);
    }
}