<?php

namespace App\Controller\Cpanel\Events\Regenerate;

use App\Entity\AiTaskDraft;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\ReformulationRecord;
use App\Entity\User;
use App\Repository\SwotRepository;
use App\Service\AiProblemReformulationService;
use App\Service\AiTaskDetailService;
use App\Service\AiTaskGenerateService;
use App\Service\ThinkingTools\AiDevilsAdvocateService;
use App\Service\ThinkingTools\AiReverseAssumptionsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RegenerateController extends AbstractController
{
    /**
     * @Route("/cpanel/ai-task/regenerate-task/{id}", name="ai_task_regenerate_task", methods={"POST"})
     */
    public function regenerateSingleTask(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        Security $security,
        AiTaskGenerateService $aiTaskGenerate,
        AiTaskDetailService $aiTaskDetail,
        SwotRepository $swotRepository,
        AiProblemReformulationService $reformService,
        AiDevilsAdvocateService $advocateService,
        AiReverseAssumptionsService $reverseService
    ): Response {
        // ------------------ LOAD CONTEXT ------------------
        $ctx = $this->loadContext($id, $request, $security, $doctrine, $swotRepository);

        // ------------------ HANDLE MODE -------------------
        if ($ctx['mode'] === 'alternative') {
            return $this->handleAlternativeMode($ctx, $reformService);
        }
        if ($ctx['mode'] === 'advocate') {
            return $this->handleAdvocateMode($ctx, $advocateService);
        }
        if ($ctx['mode'] === 'reverse') {
            return $this->handleReverseMode($ctx, $reverseService);
        }

        // Default = full regeneration mode
        return $this->handleNewMode($ctx, $aiTaskGenerate, $aiTaskDetail);
    }

    private function loadContext(
        int $id,
        Request $request,
        Security $security,
        ManagerRegistry $doctrine,
        SwotRepository $swotRepo
    ): array {
        $em = $doctrine->getManager();

        // ---- EVENT ----
        /** @var Event|null $event */
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // ---- USER ----
        /** @var User $user */
        $user = $security->getUser();
        if (!$user || $event->getUser() !== $user) {
            throw $this->createAccessDeniedException('Access denied');
        }

        // ---- ENSEMBLE ----
        $ensemble = $event->getEnsemble();
        if (!$ensemble || !$ensemble->isAiGenerated()) {
            throw new \RuntimeException("This event is not part of an AI-generated ensemble");
        }

        // ---- AI TASK DRAFT ----
        $draft = $em->getRepository(AiTaskDraft::class)->findOneBy([
            'ensemble' => $ensemble,
            'user'     => $user
        ]);

        if (!$draft) {
            throw new \RuntimeException("AI draft not found for this ensemble");
        }

        $tasks = json_decode($draft->getTasksJson(), true);
        if (!is_array($tasks)) {
            throw new \RuntimeException("Corrupted draft JSON");
        }

        // ---- FIND TASK INDEX ----
        $taskIndex = null;
        foreach ($tasks as $i => $t) {
            if ((string)$t['title'] === (string)$event->getTitle()) {
                $taskIndex = $i;
                break;
            }
        }

        if ($taskIndex === null) {
            throw new \RuntimeException("Task not found inside AiTaskDraft");
        }

        // ---- SWОT ----
        $swot = $swotRepo->findOneBy(['user' => $user]);

        // ---- STRATEGY ----
        $strategy = method_exists($draft, 'getStrategy')
            ? (string) $draft->getStrategy()
            : 'career';

        // ---- EXISTING TITLES ----
        $existingTitles = [];
        foreach ($tasks as $i => $t) {
            if ($i !== $taskIndex && isset($t['title'])) {
                $existingTitles[] = (string)$t['title'];
            }
        }

        return [
            'em'            => $em,
            'request'       => $request,
            'event'         => $event,
            'user'          => $user,
            'ensemble'      => $ensemble,
            'draft'         => $draft,
            'tasks'         => $tasks,
            'taskIndex'     => $taskIndex,
            'swot'          => $swot,
            'strategy'      => $strategy,
            'existingTitles'=> $existingTitles,
            'mode'          => (string) $request->request->get('regen_mode', 'new'),
            'notes'         => (string) $request->request->get('aiNotes', ''),
        ];
    }

    private function handleAlternativeMode(
        array $ctx,
        AiProblemReformulationService $reformService
    ): Response
    {
        $em = $ctx['em'];
        $task = $ctx['tasks'][$ctx['taskIndex']];
        $user = $ctx['user'];
        $event = $ctx['event'];
        $draft = $ctx['draft'];
        $tasks = $ctx['tasks'];
        $taskIndex = $ctx['taskIndex'];

        // ---------------- 0) Check if already exists ----------------
        $existingRecord = $em->getRepository(ReformulationRecord::class)->findOneBy([
            'event' => $event,
            'user'  => $user,
            'method' => 'alternative',
        ]);
        // If exists, delete it
        if ($existingRecord) {
            $em->remove($existingRecord);
            $em->flush();
        }

        // ---------------- 1) Generate alternative formulations ----------------
        $result = $reformService->generateProblemFormulations(
            $task['title'],
            $task['idea'],
            $ctx['notes'],
            $user->getLang(),
            6
        );

        $alternatives = $result['alternatives'];

        // Original problem
        $problemResult = $reformService->makeProblemQuestionFromEvent(
            $event->getTitle(),
            $event->getDescription(),
            $user->getLang()
        );
        $problemStatement = $problemResult['problem'] ?? null;

        // Сохраняем в ReformulationRecord
        $record = new ReformulationRecord();
        $record->setEvent($event);
        $record->setUser($user);
        $record->setAlternatives($alternatives);
        $record->setProblem($problemStatement);
        $record->setMethod('alternative');
        $record->setCreatedAt(new \DateTimeImmutable());
        $record->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($record);
        $em->flush();

        // ---------------- 5) Redirect to event public page ----------------
        return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
    }

    private function handleAdvocateMode(
        array $ctx,
        AiDevilsAdvocateService $advocateService
    ): Response
    {
        $em = $ctx['em'];
        $user = $ctx['user'];
        $event = $ctx['event'];

        // ---------------- 0) Check if already exists ----------------
        $existingRecord = $em->getRepository(ReformulationRecord::class)->findOneBy([
            'event' => $event,
            'user'  => $user,
            'method' => 'advocate',
        ]);
        // If exists, delete it
        if ($existingRecord) {
            $em->remove($existingRecord);
            $em->flush();
        }

        // ---------------- 1) Generate advocate advices ----------------
        $result = $advocateService->generateDevilsAdvocateCritique(
            $event->getTitle(),
            $event->getDescription(),
            $user->getLang(),
            6
        );
        $critiques = $result['critiques'];

        // Сохраняем в ReformulationRecord
        $record = new ReformulationRecord();
        $record->setEvent($event);
        $record->setUser($user);
        $record->setAlternatives($critiques);
        $record->setMethod('advocate');
        $record->setCreatedAt(new \DateTimeImmutable());
        $record->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($record);
        $em->flush();

        // ---------------- 5) Redirect to event public page ----------------
        return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
    }

    private function handleReverseMode(
        array $ctx,
        AiReverseAssumptionsService $reverseService
    ): Response
    {
        $em = $ctx['em'];
        $user = $ctx['user'];
        $event = $ctx['event'];

        // ---------------- 0) Check if already exists ----------------
        $existingRecord = $em->getRepository(ReformulationRecord::class)->findOneBy([
            'event' => $event,
            'user'  => $user,
            'method' => 'reverse',
        ]);
        // If exists, delete it
        if ($existingRecord) {
            $em->remove($existingRecord);
            $em->flush();
        }

        // ---------------- 1) Generate advocate advices ----------------
        $result = $reverseService->generateReverseAssumptions(
            $event->getTitle(),
            $event->getDescription(),
            $user->getLang(),
            6
        );
        $assumptions = $result['assumptions'];

        // Сохраняем в ReformulationRecord
        $record = new ReformulationRecord();
        $record->setEvent($event);
        $record->setUser($user);
        $record->setAlternatives($assumptions);
        $record->setMethod('reverse');
        $record->setCreatedAt(new \DateTimeImmutable());
        $record->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($record);
        $em->flush();

        // ---------------- 5) Redirect to event public page ----------------
        return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
    }


    private function handleNewMode(
        array $ctx,
        AiTaskGenerateService $aiTaskGenerate,
        AiTaskDetailService $aiTaskDetail
    ): Response {
        $em        = $ctx['em'];
        $tasks     = $ctx['tasks'];
        $taskIndex = $ctx['taskIndex'];
        $draft     = $ctx['draft'];
        $event     = $ctx['event'];
        $user      = $ctx['user'];

        // ---------------- 0) Check if already exists ----------------
        $existingRecord = $em->getRepository(ReformulationRecord::class)->findBy([
            'event' => $event,
            'user'  => $user,
        ]);
        // If exists, delete it
        if ($existingRecord) {
            foreach ($existingRecord as $record) {
                $em->remove($record);
            }
            $em->flush();
        }

        // ---- 1) REGULAR REGENERATE ----
        $newTask = $aiTaskGenerate->regenerateSingleTask(
            $ctx['notes'],
            $user->getLang(),
            $ctx['strategy'],
            $user->getArtisan(),
            $user->getDescription(),
            $ctx['swot'],
            $ctx['existingTitles']
        );

        // ---- 2) TASK DETAILS ----
        $details = $aiTaskDetail->generateTaskDetails(
            $newTask['title'],
            $newTask['idea'],
            $user->getLang()
        );

        // ---- 3) UPDATE AiTaskDraft ----
        $tasks[$taskIndex] = [
            'title'       => $newTask['title'],
            'idea'        => $newTask['idea'],
            'description' => $details['description'],
            'instruction' => $details['instruction'],
        ];

        $draft->setTasksJson(json_encode($tasks, JSON_UNESCAPED_UNICODE));
        $draft->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();

        // ---- 4) UPDATE Event ----
        $event->setTitle($newTask['title']);
        $event->setDescription($details['description']);

        if (method_exists($event, 'setInstruction')) {
            $event->setInstruction($details['instruction']);
        }

        $em->flush();

        // ---- 5) Redirect ----
        return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);}
}