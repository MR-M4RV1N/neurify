<?php

namespace App\Controller\Cpanel;

use App\Entity\ResourceMap;
use App\Repository\ResourceMapRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceMapController extends AbstractController
{
    /**
     * @Route("/resource-map", name="app_resource_map_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ResourceMapRepository $repo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in.');
        }

        // 1) ищем запись пользователя или создаём новую
        $rm = $repo->findOneBy(['user' => $user]);
        $isNew = false;

        if (!$rm) {
            $rm = new ResourceMap();
            $rm->setUser($user);
            $now = new \DateTimeImmutable();
            $rm->setCreatedAt($now);
            $rm->setUpdatedAt($now);
            $isNew = true;
        }

        // 2) простая форма (без отдельного FormType)
        $form = $this->createFormBuilder($rm)
            ->add('content', TextareaType::class, [
                'label' => 'Resource map',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows'  => 10,
                    'placeholder' => 'Describe your resource map…',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => $isNew ? 'Create' : 'Save',
                'attr'  => ['class' => 'btn btn-primary mt-3'],
            ])
            ->getForm();

        $form->handleRequest($request);

        // 3) сохранение
        if ($form->isSubmitted() && $form->isValid()) {
            $rm->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($rm);
            $em->flush();

            $this->addFlash('success', 'Resource map saved.');
            return $this->redirectToRoute('ai_consulting');
        }

        return $this->render('cpanel/resource_map/resource_map.html.twig', [
            'form' => $form->createView(),
            'resourceMap' => $rm,
        ]);
    }
}