<?php

namespace App\Controller\Cpanel;

use App\Entity\AiConsultingResult;
use App\Entity\AiTaskDraft;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\AiComment;
use App\Entity\MbtiResult;
use App\Entity\ResourceMap;
use App\Entity\Swot;
use App\Entity\User;
use App\Repository\EnsembleRepository;
use App\Repository\SwotRepository;
use App\Service\AiCommentService;
use App\Service\AiConsultingService;
use App\Service\AiTaskDetailService;
use App\Service\AiTaskGenerateService;
use App\Service\AiTaskService;
use App\Service\EnsembleProgressService;
use App\Service\StepwiseService;
use App\Service\WebhookPublisher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AiTaskController extends AbstractController
{
    /**
     * @Route("/cpanel/ai-task", name="ai_task", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // Проверяем, есть ли уже категория со сгенерированными ИИ задачами для текущего пользователя
        $checkEnsemble = $doctrine->getRepository(Ensemble::class)->findOneBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => false]);
        if($checkEnsemble) {
            // Если такая категория существует, перенаправляем пользователя на страницу сгенерированных задач
            return $this->redirectToRoute('ai_task_open');
        }
        else {
            // Если такой категории нет, отображаем страницу с предложением сгенерировать задачи
            return $this->render('cpanel/ai_task/index.html.twig', [
                'checkProfileDescription' => !empty($this->getUser()->getDescription()),
                'checkSwot' => $this->getDoctrine()->getRepository(Swot::class)->count(['user' => $this->getUser()]) > 0,
            ]);
        }
    }

    /**
     * @Route("/cpanel/ai-task/new", name="ai_task_new", methods={"GET"})
     */
    public function aiTaskNew(): Response
    {
        // Если такой категории нет, отображаем страницу с предложением сгенерировать задачи
        return $this->render('cpanel/ai_task/index.html.twig', [
            'checkProfileDescription' => !empty($this->getUser()->getDescription()),
            'checkSwot' => $this->getDoctrine()->getRepository(Swot::class)->count(['user' => $this->getUser()]) > 0,
        ]);
    }

    /**
     * Шаг 1.
     * Генерируем COMMENT + TITLES + IDEAS.
     * Отображаем titles.html.twig
     *
     * @Route("/cpanel/ai-task/generate", name="ai_task_generate", methods={"POST"})
     */
    public function generate(
        Request $request,
        AiTaskGenerateService $aiTaskGenerate,
        ManagerRegistry $doctrine,
        SwotRepository $swotRepository
    ): Response {
        if (!$this->isCsrfTokenValid('ai_task', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException("Bad CSRF token");
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }

        // Заметки пользователя
        $notes = (string) $request->request->get('aiNotes', '');

        // Стратегия (career, focus, creativity…)
        $strategy = (string) $request->request->get('strategy', 'career');

        // SWOT пользователя (может быть null)
        $swot = $swotRepository->findOneBy(['user' => $user]);

        // --- AI: COMMENT + TITLES + IDEAS ---
        try {
            $result = $aiTaskGenerate->generateTaskTitles(
                $notes,
                (string) $user->getLang(),
                (string) $user->getArtisan(),
                (string) $user->getDescription(),
                $swot,
                $strategy
            );
        } catch (\Throwable $e) {
            dd('AI ERROR:', $e->getMessage());
        }
        // $result = ['comment' => '...', 'tasks' => [ ['title' => '...', 'idea' => '...'], ... ] ]

        // --- Сохраняем черновик ---
        $draft = new AiTaskDraft();
        $draft->setUser($user);
        // Если потом привяжешь к Ensemble — подставишь сюда объект
        $draft->setEnsemble(null);
        $draft->setComment($result['comment']);
        $draft->setTasksJson(json_encode($result['tasks'], JSON_UNESCAPED_UNICODE));
        $draft->setNotes($notes);
        if (method_exists($draft, 'setStrategy')) {
            $draft->setStrategy($strategy);
        }
        if (method_exists($draft, 'setLang')) {
            $draft->setLang($this->getUser()->getLang());
        }
        $now = new \DateTimeImmutable();
        if (method_exists($draft, 'setCreatedAt')) {
            $draft->setCreatedAt($now);
        }
        if (method_exists($draft, 'setUpdatedAt')) {
            $draft->setUpdatedAt($now);
        }

        $em = $doctrine->getManager();
        try {
            $em->persist($draft);
            $em->flush();
        } catch (\Throwable $e) {
            dd("Doctrine error:", $e->getMessage(), $e);
        }

        return $this->redirectToRoute('ai_task_titles', [
            'id' => $draft->getId()
        ]);
    }

    /**
     * @Route("/cpanel/ai-task/titles/{id}", name="ai_task_titles", methods={"GET"})
     */
    public function titles(
        int $id,
        ManagerRegistry $doctrine,
        Security $security
    ): Response {
        $draft = $doctrine->getRepository(AiTaskDraft::class)->find($id);

        if (!$draft) {
            throw $this->createNotFoundException();
        }

        if ($draft->getUser() !== $security->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('cpanel/ai_task/titles.html.twig', [
            'draft'   => $draft,
            'tasks'   => json_decode($draft->getTasksJson(), true),
            'comment' => $draft->getComment(),
            'user'    => $security->getUser()
        ]);
    }

    /**
     * Шаг 2.
     * Генерируем DESCRIPTION + INSTRUCTION по title + idea.
     * Возвращаем result.html.twig
     *
     * @Route("/cpanel/ai-task/describe/{id}", name="ai_task_describe", methods={"POST","GET"})
     */
    public function generateDescription(
        int $id,
        AiTaskDetailService $aiTaskDetail,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();

        /** @var AiTaskDraft|null $draft */
        $draft = $em->getRepository(AiTaskDraft::class)->find($id);
        if (!$draft) {
            throw $this->createNotFoundException('Draft not found');
        }

        /** @var \App\Entity\User $user */
        $user = $draft->getUser();
        if (!$user || $user !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access denied to this draft');
        }

        $lang = (string) $user->getLang();

        // --- Загружаем titles + ideas из черновика ---
        $tasksRaw = json_decode($draft->getTasksJson(), true);
        if (!is_array($tasksRaw)) {
            $tasksRaw = [];
        }

        $finalTasks = [];

        foreach ($tasksRaw as $task) {
            $title = isset($task['title']) ? (string) $task['title'] : '';
            $idea  = isset($task['idea']) ? (string) $task['idea'] : '';

            if ($title === '') {
                continue;
            }

            // --- AI: description + instruction для данной задачи ---
            $detail = $aiTaskDetail->generateTaskDetails($title, $idea, $lang);

            $finalTasks[] = [
                'title'       => $title,
                'idea'        => $idea,
                'description' => isset($detail['description']) ? (string) $detail['description'] : '',
                'instruction' => isset($detail['instruction']) ? (string) $detail['instruction'] : '',
            ];
        }

        // --- Обновляем черновик полным набором задач ---
        $draft->setTasksJson(json_encode($finalTasks, JSON_UNESCAPED_UNICODE));
        if (method_exists($draft, 'setUpdatedAt')) {
            $draft->setUpdatedAt(new \DateTimeImmutable());
        }
        $em->flush();

        return $this->redirectToRoute('ai_task_result', [
            'id' => $draft->getId()
        ]);
    }

    /**
     * @Route("/cpanel/ai-task/result/{id}", name="ai_task_result")
     */
    public function result(int $id, ManagerRegistry $doctrine, Security $security)
    {
        $draft = $doctrine->getRepository(AiTaskDraft::class)->find($id);

        if (!$draft) {
            throw $this->createNotFoundException();
        }

        if ($draft->getUser() !== $security->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $tasks = json_decode($draft->getTasksJson(), true) ?? [];

        return $this->render('cpanel/ai_task/result.html.twig', [
            'draft' => $draft,
            'tasks' => $tasks,
            'comment' => $draft->getComment(),
        ]);
    }

    /**
     * @Route("/cpanel/ai-task/update/{id}", name="ai_task_update", methods={"POST"})
     */
    public function update(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        Security $security
    ): Response {
        $em = $doctrine->getManager();

        /** @var AiTaskDraft|null $draft */
        $draft = $em->getRepository(AiTaskDraft::class)->find($id);
        if (!$draft) {
            throw $this->createNotFoundException('Draft not found');
        }

        /** @var User $user */
        $user = $security->getUser();
        if (!$user || $draft->getUser() !== $user) {
            throw $this->createAccessDeniedException('Access denied');
        }

        // CSRF
        if (!$this->isCsrfTokenValid('ai_task_update', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Bad CSRF token');
        }

        // Получаем массив tasks из формы
        $postedTasks = $request->request->get('tasks', []);
        if (!is_array($postedTasks)) {
            throw new \RuntimeException("Invalid tasks format");
        }

        // Загружаем старые tasksJson из draft — чтобы сохранить idea!
        $existingTasks = json_decode($draft->getTasksJson(), true);
        if (!is_array($existingTasks)) {
            $existingTasks = [];
        }

        $updated = [];

        foreach ($postedTasks as $index => $task) {
            $title       = trim($task['title'] ?? '');
            $description = trim($task['description'] ?? '');
            $instruction = trim($task['instruction'] ?? '');

            if ($title === '') {
                continue; // пропустить пустые задачи
            }

            // Получаем idea из старого массива
            $idea = isset($existingTasks[$index]['idea'])
                ? (string)$existingTasks[$index]['idea']
                : '';

            $updated[] = [
                'title'       => $title,
                'idea'        => $idea,        // важно: сохраняем
                'description' => $description,
                'instruction' => $instruction
            ];
        }

        // Обновляем draft
        $draft->setTasksJson(json_encode($updated, JSON_UNESCAPED_UNICODE));

        if (method_exists($draft, 'setUpdatedAt')) {
            $draft->setUpdatedAt(new \DateTimeImmutable());
        }

        $em->flush();

        return $this->redirectToRoute('ai_task_save', ['id' => $draft->getId()]);
    }

    /**
     * @Route("/cpanel/ai-task/save/{id}", name="ai_task_save", methods={"POST","GET"})
     */
    public function save(
        int $id,
        ManagerRegistry $doctrine,
        Security $security
    ): Response {
        $em = $doctrine->getManager();

        /** @var AiTaskDraft|null $draft */
        $draft = $em->getRepository(AiTaskDraft::class)->find($id);
        if (!$draft) {
            throw $this->createNotFoundException('Draft not found');
        }

        /** @var User $user */
        $user = $security->getUser();
        if (!$user || $draft->getUser() !== $user) {
            throw $this->createAccessDeniedException('Access denied');
        }

        // Если уже сохранено — не создаём Event повторно
        if ($draft->isSaved()) {
            return $this->redirectToRoute('ai_task_open');
        }

        // Снимаем черновой статус с предыдущей категории, если есть
//        $ensemble = $em->getRepository(Ensemble::class)->findOneBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => false]);
//        if ($ensemble) {
//            $ensemble->setDraft(true);
//            $em->flush();
//        }

        // 1) Создаём Ensemble, если его нет
        $ensemble = $draft->getEnsemble();

        if ($ensemble === null) {
            $ensemble = new Ensemble();
            $ensemble->setAuthor($user);

            $lastEnsemble = $em->getRepository(Ensemble::class)->findOneBy(
                [
                    'author' => $user,
                    'aiGenerated' => true,
                    'draft' => false,
                ],
                [
                    'createdAt' => 'DESC',
                    'id' => 'DESC',
                ]
            );

            $nextNumber = 1;

            if ($lastEnsemble) {
                $lastTitle = (string) $lastEnsemble->getTitle();
                if (preg_match('/(\d+)\s*$/u', $lastTitle, $m)) {
                    $nextNumber = ((int) $m[1]) + 1;
                }
            }

            $ensemble->setTitle(sprintf('NEURIFY TASKS %03d', $nextNumber));
            $ensemble->setDescription('Automātiski ģenerēti individuālie uzdevumi');
            $ensemble->setImage('default.jpg');
            $ensemble->setIsClosed(false);
            $ensemble->setCreatedAt(new \DateTimeImmutable());
            $ensemble->setSelected(false);
            $ensemble->setAiGenerated(true);
            $ensemble->setDraft(false);

            $em->persist($ensemble);

            $draft->setEnsemble($ensemble);
        }

        // 2) Загружаем задачи
        $tasks = json_decode($draft->getTasksJson(), true);
        if (!is_array($tasks)) {
            throw new \RuntimeException("Invalid tasks JSON in draft");
        }

        // 3) Создаём Event для каждой задачи
        foreach ($tasks as $task) {
            $title = trim($task['title'] ?? '');
            if ($title === '') {
                continue;
            }

            $description = trim($task['description'] ?? '');
            $instruction = trim($task['instruction'] ?? '');

            $event = new Event();
            $event->setUser($user);
            $event->setTitle($title);
            $event->setDescription($description);

            if (method_exists($event, 'setInstruction')) {
                $event->setInstruction($instruction);
            }

            $event->setPublic(false);
            $event->setTask(true);
            $event->setPortfolio(false);
            $event->setPower(0);
            $event->setPriority(0);
            $event->setType(1);
            $event->setPinned(false);

            $event->setLang($user->getLang() ?? 'lv');
            $event->setDate(new \DateTime());
            $event->setTime(new \DateTime('00:00'));

            // ВАЖНО: привязываем Event к категории
            $event->setEnsemble($ensemble);
            $event->setAiGenerated(true);
            $event->setAiCompleted(false);
            $event->setHidden(true);

            $em->persist($event);
        }

        // 4) Помечаем draft как сохранённый
        $draft->setIsSaved(true);
        $draft->setUpdatedAt(new \DateTime());

        $em->flush();

        return $this->redirectToRoute('ai_task_open');
    }


    /**
     * @Route("/cpanel/ai-task/open", name="ai_task_open", methods={"GET"})
     */
    public function open(ManagerRegistry $doctrine, StepwiseService $stepwise, EnsembleProgressService $progress): Response
    {
        $ensemble = $doctrine->getRepository(Ensemble::class)->findOneBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => false]);

        // проверяем прогресс
        $completed = $progress->checkProgress($this->getUser(), $this->getUser(), $ensemble ?? null);
        // если всё выполнено и шаблон ещё НЕ создан → делаем запись и редирект
        if ($completed === 1) {
            return $this->redirectToRoute('ensemble_complete_congratulations');
        }

        $data = $stepwise->buildForEnsemble($ensemble, $this->getUser());

        $profile = $doctrine->getRepository(User::class)->find(102);
        $profile->setDescription('Ņēmot vērā jūsu profila aprakstu un izaicinājumus, šeit ir AI ģenerēts uzdevumu saraksts, kas pielāgots jūsu vajadzībām un mērķiem.');
        $profile->setImage('nf-ai.png');
        $profile->setArtisan('Individuālo izaicinājumu konsultants');
        $profile->setFirstname('NEURIFY');
        $profile->setLastname('MASTER');
        $data['profile'] = $profile;
        $data['level'] = 20;
        $data['ensemble'] = $ensemble;

        return $this->render('/cpanel/events/user/stepwise/list.html.twig', $data);
    }

    /**
     * @Route("/cpanel/ai-task/regenerate/{id}", name="ai_task_regenerate", methods={"POST"})
     */
    public function regenerate(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        Security $security,
        AiTaskGenerateService $aiTaskGenerate,
        SwotRepository $swotRepository
    ): Response {
        $em = $doctrine->getManager();

        /** @var AiTaskDraft|null $draft */
        $draft = $em->getRepository(AiTaskDraft::class)->find($id);
        if (!$draft) {
            throw $this->createNotFoundException('Draft not found');
        }

        /** @var User $user */
        $user = $security->getUser();
        if (!$user || $draft->getUser() !== $user) {
            throw $this->createAccessDeniedException('Access denied');
        }

        if (!$this->isCsrfTokenValid('ai_task_regenerate', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Bad CSRF token');
        }

        // новые рекомендации пользователя
        $notes = (string)$request->request->get('aiNotes', '');

        // стратегия сохраняется в draft или из request
        $strategy = $request->request->get('strategy', 'career');

        // загружаем SWOT
        $swot = $swotRepository->findOneBy(['user' => $user]);

        // генерируем НОВЫЕ titles+idea
        $titles = $aiTaskGenerate->generateTaskTitles(
            $notes,
            $user->getLang(),
            $user->getArtisan(),
            $user->getDescription(),
            $swot,
            $strategy
        );

        // загружаем старый tasksJson — чтобы сохранить descriptions/instructions
        $oldTasks = json_decode($draft->getTasksJson(), true);
        if (!is_array($oldTasks)) {
            $oldTasks = [];
        }

        // собираем обновлённый список
        $updated = [];
        foreach ($titles as $index => $task) {

            // старые данные (если были)
            $oldDescription = isset($oldTasks[$index]['description']) ? $oldTasks[$index]['description'] : '';
            $oldInstruction = isset($oldTasks[$index]['instruction']) ? $oldTasks[$index]['instruction'] : '';

            $updated[] = [
                'title'       => $task['title'],
                'idea'        => $task['idea'],
                'description' => $oldDescription,
                'instruction' => $oldInstruction,
            ];
        }

        // обновляем draft
        $draft->setTasksJson(json_encode($updated, JSON_UNESCAPED_UNICODE));
        $draft->setNotes($notes);
        $draft->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        // возвращаем пользователя на result
        return $this->redirectToRoute('ai_task_result', ['id' => $draft->getId()]);
    }

    /**
     * @Route("/cpanel/ai-task/relocate/{id}", name="ai_task_relocate", methods={"POST"})
     */
    public function relocate(
        int $id,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();
        $ensemble = $em->getRepository(Ensemble::class)->findOneBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => false]);
        if ($ensemble) {
            $ensemble->setAiGenerated(false);
            $ensemble->setDraft(true);
            $em->flush();
        }
        else{
            throw $this->createNotFoundException('Ensemble not found');
        }

        return $this->redirectToRoute('ai_task');
    }
}