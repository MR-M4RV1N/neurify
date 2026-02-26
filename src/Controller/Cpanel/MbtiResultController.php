<?php

namespace App\Controller\Cpanel;

use App\Entity\MbtiResult;
use App\Repository\MbtiResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MbtiResultController extends AbstractController
{
    /**
     * @Route("/cpanel/mbti", name="app_mbti_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MbtiResultRepository $repo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in.');
        }

        // 1) Находим запись пользователя или создаём новую
        $mbti = $repo->findOneBy(['user' => $user]);
        $isNew = false;

        if (!$mbti) {
            $mbti = new MbtiResult();
            $mbti->setUser($user);
            $now = new \DateTimeImmutable();
            $mbti->setCreatedAt($now);
            $mbti->setUpdatedAt($now);
            $isNew = true;
        }

        // 2) Форма без отдельного FormType
        $form = $this->createFormBuilder($mbti)
            ->add('content', TextareaType::class, [
                'label' => '16Personalities (MBTI)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows'  => 10,
                    'placeholder' => 'e.g. INTJ-A — concise interpretation or paste the test summary here…',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => $isNew ? 'Create' : 'Save',
                'attr'  => ['class' => 'btn btn-primary mt-3'],
            ])
            ->getForm();

        $form->handleRequest($request);

        // 3) Сохранение
        if ($form->isSubmitted() && $form->isValid()) {
            $mbti->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($mbti);
            $em->flush();

            $this->addFlash('success', 'MBTI result saved.');
            return $this->redirectToRoute('ai_consulting');
        }

        return $this->render('cpanel/mbti/mbti.html.twig', [
            'form'  => $form->createView(),
            'mbti'  => $mbti,
            'isNew' => $isNew,
        ]);
    }
}