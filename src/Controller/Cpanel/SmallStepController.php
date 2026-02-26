<?php

namespace App\Controller\Cpanel;

use App\Entity\SmallStep;
use App\Form\SmallStepType;
use App\Repository\SmallStepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cpanel/small-steps")
 */
class SmallStepController extends AbstractController
{
    /**
     * @Route("/index", name="cpanel_small_step_index", methods={"GET"})
     */
    public function index(
        Request             $request,
        Security            $security,
        ManagerRegistry     $doctrine
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        // SMALL STEPS: last 12 + form
        // --------------------------
        /** @var SmallStepRepository $smallStepRepo */
        $smallStepRepo = $doctrine->getRepository(SmallStep::class);
        $sectorRepo    = $doctrine->getRepository(\App\Entity\LifeWheelSector::class);

        $sectors = $sectorRepo->findAll();

        $criteria = ['user' => $this->getUser(), 'draft' => false];

        $categoryId = $request->query->get('category');
        if ($categoryId) {
            $category = $sectorRepo->find($categoryId);
            if ($category) {
                $criteria['category'] = $category;
            }
        }

        // 12 последних, сверху самая свежая (createdAt DESC)
        $smallSteps = $smallStepRepo->findBy(
            $criteria,
            ['date' => 'DESC', 'id'   => 'DESC'],
            12
        );

        return $this->render('cpanel/small_step/index.html.twig', [
            'items'           => $smallSteps,
            'mode'            => 'steps',
            'sectors'         => $sectors,
            'currentCategory' => $categoryId,
        ]);
    }

    /**
     * @Route("/ideas", name="cpanel_small_step_ideas", methods={"GET"})
     */
    public function idea(
        Request         $request,
        Security        $security,
        ManagerRegistry $doctrine
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        // SMALL STEPS: last 12 + form
        // --------------------------
        /** @var SmallStepRepository $smallStepRepo */
        $smallStepRepo = $doctrine->getRepository(SmallStep::class);
        $sectorRepo    = $doctrine->getRepository(\App\Entity\LifeWheelSector::class);

        $sectors = $sectorRepo->findAll();

        $criteria = ['user' => $this->getUser(), 'draft' => true];

        $categoryId = $request->query->get('category');
        if ($categoryId) {
            $category = $sectorRepo->find($categoryId);
            if ($category) {
                $criteria['category'] = $category;
            }
        }

        // 12 последних, сверху самая свежая (createdAt DESC)
        $smallIdeas = $smallStepRepo->findBy(
            $criteria,
            ['date' => 'DESC', 'id'   => 'DESC'],
            12
        );

        return $this->render('cpanel/small_step/index.html.twig', [
            'items'           => $smallIdeas,
            'mode'            => 'ideas',
            'sectors'         => $sectors,
            'currentCategory' => $categoryId,
        ]);
    }

    /**
     * @Route("/new", name="cpanel_small_step_new", methods={"GET","POST"})
     */
    public function new(
        Request                $request,
        EntityManagerInterface $em,
        Security               $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        $step = new SmallStep();

        // type=idea|step
        $type = (string)$request->query->get('type', 'step');
        $step->setDraft($type === 'idea');

        $form = $this->createForm(SmallStepType::class, $step, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Владелец всегда задаётся на сервере
            $step->setUser($user);

            $em->persist($step);
            $em->flush();

            $this->addFlash('success', 'Small step created.');

            if ($type === 'idea') {
                return $this->redirectToRoute('cpanel_small_step_ideas');
            } else {
                return $this->redirectToRoute('cpanel_small_step_index');
            }
        }

        return $this->render('cpanel/small_step/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cpanel_small_step_edit", methods={"GET","POST"})
     */
    public function edit(
        int                    $id,
        Request                $request,
        SmallStepRepository    $repo,
        EntityManagerInterface $em,
        Security               $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        $step = $repo->find($id);
        if (!$step) {
            throw $this->createNotFoundException('Small step not found.');
        }

        if ($step->getUser() !== $user) {
            throw $this->createAccessDeniedException('Not allowed.');
        }

        $form = $this->createForm(SmallStepType::class, $step, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // На всякий случай не даём перезаписать владельца
            $step->setUser($user);

            $em->flush();

            $this->addFlash('success', 'Small step updated.');

            if ($step->isDraft() === true) {
                return $this->redirectToRoute('cpanel_small_step_ideas');
            } else {
                return $this->redirectToRoute('cpanel_small_step_index');
            }
        }

        return $this->render('cpanel/small_step/edit.html.twig', [
            'step' => $step,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="cpanel_small_step_show", methods={"GET"})
     */
    public function show(
        int                 $id,
        SmallStepRepository $repo,
        Security            $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        $step = $repo->find($id);
        if (!$step) {
            throw $this->createNotFoundException('Small step not found.');
        }

        if ($step->getUser() !== $user) {
            throw $this->createAccessDeniedException('Not allowed.');
        }

        return $this->render('cpanel/small_step/show.html.twig', [
            'step' => $step,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="cpanel_small_step_delete", methods={"POST"})
     */
    public function delete(
        int                    $id,
        Request                $request,
        SmallStepRepository    $repo,
        EntityManagerInterface $em,
        Security               $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        $step = $repo->find($id);
        if (!$step) {
            throw $this->createNotFoundException('Small step not found.');
        }

        if ($step->getUser() !== $user) {
            throw $this->createAccessDeniedException('Not allowed.');
        }

        // Отдельный CSRF для delete
        if (!$this->isCsrfTokenValid('delete_small_step_' . $step->getId(), (string)$request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $em->remove($step);
        $em->flush();

        $this->addFlash('success', 'Small step deleted.');

        if ($step->isDraft() === true) {
            return $this->redirectToRoute('cpanel_small_step_ideas');
        } else {
            return $this->redirectToRoute('cpanel_small_step_index');
        }
    }
    /**
     * @Route("/categories", name="cpanel_small_step_categories", methods={"GET"})
     */
    public function categories(
        Request         $request,
        Security        $security,
        ManagerRegistry $doctrine
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        $mode = $request->query->get('mode', 'steps');
        $isDraft = ($mode === 'ideas');

        /** @var SmallStepRepository $smallStepRepo */
        $smallStepRepo = $doctrine->getRepository(SmallStep::class);
        $sectorRepo    = $doctrine->getRepository(\App\Entity\LifeWheelSector::class);

        $sectors = $sectorRepo->findAll();
        $stats   = [];

        foreach ($sectors as $sector) {
            $count = $smallStepRepo->count([
                'user'     => $user,
                'category' => $sector,
                'draft'    => $isDraft
            ]);

            // Only show categories with records? Or all? Let's show all for now.
            $stats[] = [
                'sector' => $sector,
                'count'  => $count
            ];
        }

        // Add "No Category" stats if needed?
        // For now, stick to sectors.

        return $this->render('cpanel/small_step/categories.html.twig', [
            'stats' => $stats,
            'mode'  => $mode
        ]);
    }
}
