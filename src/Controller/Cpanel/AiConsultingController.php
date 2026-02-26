<?php

namespace App\Controller\Cpanel;

use App\Consulting\Model\Disc\DiscPromptProvider;
use App\Consulting\Model\Mbti\MbtiPromptProvider;
use App\Consulting\Model\Thinker\ThinkerPromptProvider;
use App\Consulting\Model\Wealth\WealthPromptProvider;
use App\Consulting\Model\PublicService\PublicServicePromptProvider;
use App\Consulting\Registry\ConsultingModelRegistry;
use App\Entity\AiCareerConsultingResult;
use App\Entity\AiConsultingResult;
use App\Entity\ConsultingInsight;
use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\AiComment;
use App\Entity\EventRepeated;
use App\Entity\MbtiResult;
use App\Entity\ResourceMap;
use App\Entity\Swot;
use App\Entity\User;
use App\Service\AiCommentService;
use App\Service\AiConsultingService;
use App\Service\AiTaskDetailService;
use App\Service\AiTaskGenerateService;
use App\Service\AiTaskService;
use App\Service\WebhookPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AiConsultingController extends AbstractController
{
    /**
     * @Route("/cpanel/ai-consulting", name="ai_consulting", methods={"GET"})
     */
    public function aiConsulting(MbtiPromptProvider $mbtiPromptProvider, DiscPromptProvider $discPromptProvider, ThinkerPromptProvider $thinkerPromptProvider, PublicServicePromptProvider $publicServicePromptProvider): Response
    {
        $user = $this->getUser();
        $lang = $user->getLang();

        $mbtiStyles = ['Se', 'Si', 'Ne', 'Ni', 'Te', 'Ti', 'Fe', 'Fi'];
        $mbtiStyleDescriptions = [];
        $mbtiStyleLabels = [];
        foreach ($mbtiStyles as $style) {
            $mbtiStyleDescriptions[$style] = $mbtiPromptProvider->getStyleDescription($style, $lang);
            $mbtiStyleLabels[$style] = $mbtiPromptProvider->getStyleShortLabel($style, $lang);
        }

        $discStyles = ['D', 'I', 'S', 'C'];
        $discStyleDescriptions = [];
        $discStyleLabels = [];
        foreach ($discStyles as $style) {
            $discStyleDescriptions[$style] = $discPromptProvider->getStyleDescription($style, $lang);
            $discStyleLabels[$style] = $discPromptProvider->getStyleShortLabel($style, $lang);
        }

        $thinkerStyles = ['berne', 'taleb', 'jung'];
        $thinkerStyleDescriptions = [];
        $thinkerStyleLabels = [];
        foreach ($thinkerStyles as $style) {
            $thinkerStyleDescriptions[$style] = $thinkerPromptProvider->getStyleDescription($style, $lang);
            $thinkerStyleLabels[$style] = $thinkerPromptProvider->getStyleShortLabel($style, $lang);
        }

        $wealthStyles = ['hill', 'clason'];
        $wealthStyleDescriptions = [];
        $wealthStyleLabels = [];
        foreach ($wealthStyles as $style) {
            $wealthStyleDescriptions[$style] = $wealthPromptProvider->getStyleDescription($style, $lang);
            $wealthStyleLabels[$style] = $wealthPromptProvider->getStyleShortLabel($style, $lang);
        }

        $publicServiceStyles = ['drucker'];
        $publicServiceStyleDescriptions = [];
        $publicServiceStyleLabels = [];
        foreach ($publicServiceStyles as $style) {
            $publicServiceStyleDescriptions[$style] = $publicServicePromptProvider->getStyleDescription($style, $lang);
            $publicServiceStyleLabels[$style] = $publicServicePromptProvider->getStyleShortLabel($style, $lang);
        }

        // DEBUG: Check if Jung is present
        // dd($thinkerStyleLabels);

        return $this->render('cpanel/ai_consulting/ai_consulting.html.twig', [
            'generatedCategories' => $this->getDoctrine()->getRepository(Ensemble::class)->findBy(['author' => $this->getUser(), 'aiGenerated' => true, 'draft' => true]),
            //'checkMbti' => $this->getDoctrine()->getRepository(MbtiResult::class)->count(['user' => $this->getUser()]) > 0,
            'checkSwot' => $this->getDoctrine()->getRepository(Swot::class)->count(['user' => $this->getUser()]) > 0,
            'checkProfileDescription' => !empty($this->getUser()->getDescription()),
            //'checkResourceMap' => $this->getDoctrine()->getRepository(ResourceMap::class)->count(['user' => $this->getUser()]) > 0,
            'checkLastMonthEvents' => $this->getDoctrine()->getRepository(Event::class)->countLastMonthEventsByUser($this->getUser()) > 0,
            'lastConsulting' => $this->getDoctrine()->getRepository(ConsultingInsight::class)->findLastRecordsByUser($this->getUser()),
            'mbtiStyleDescriptions' => $mbtiStyleDescriptions,
            'mbtiStyleLabels' => $mbtiStyleLabels,
            'discStyleDescriptions' => $discStyleDescriptions,
            'discStyleLabels' => $discStyleLabels,
            'thinkerStyleDescriptions' => $thinkerStyleDescriptions,
            'thinkerStyleLabels' => $thinkerStyleLabels,
            'wealthStyleDescriptions' => $wealthStyleDescriptions,
            'wealthStyleLabels' => $wealthStyleLabels,
            'publicServiceStyleDescriptions' => $publicServiceStyleDescriptions,
            'publicServiceStyleLabels' => $publicServiceStyleLabels,
        ]);
    }

    /**
     * @Route("/cpanel/ai_consulting/generate", name="ai_consulting_generate", methods={"POST"})
     */
    public function aiComment(
        Request $request,
        Security $security,
        ConsultingModelRegistry $consultingRegistry
    ): Response {
        // --------------------------
        // CSRF
        // --------------------------
        if (!$this->isCsrfTokenValid(
            'ai_consulting_generate',
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $user = $security->getUser();
        $lang = $user->getLang();

        // --------------------------
        // MODE
        // --------------------------
        $mode       = $request->request->get('consulting_mode', 'auto');
        $model      = $request->request->get('consulting_model');
        $freePrompt = trim((string)$request->request->get('free_prompt'));
        $mbtiStyle = trim((string) $request->request->get('mbti_style', ''));
        $discStyle = trim((string) $request->request->get('disc_style', ''));
        $thinkerStyle = trim((string) $request->request->get('thinker_style', ''));
        $publicServiceStyle = trim((string) $request->request->get('public_service_style', ''));

        if ($mode === 'health') {
            $mode = trim((string) $request->request->get('health_style', 'biohacking'));
        }

        /**
         * ==========================
         * MBTI
         * ==========================
         */
        if ($mode === 'mbti') {
            if ($mbtiStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            $request->getSession()->set('mbti_style', $mbtiStyle);

            $consultingModel = $consultingRegistry->get('mbti_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'mbti_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * RUNNING
         * ==========================
         */
        if ($mode === 'running') {
            $runningStyle = trim((string) $request->request->get('running_style', ''));
            if ($runningStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            // For now, we only support 'running_japan', but this variable approach allows future additions.
            $consultingModel = $consultingRegistry->get($runningStyle);

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => $runningStyle,
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * BIOHACKING
         * ==========================
         */
        if ($mode === 'biohacking') {
            $consultingModel = $consultingRegistry->get('biohacking_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'biohacking_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * WEALTH & SUCCESS
         * ==========================
         */
        if ($mode === 'wealth') {
            $wealthStyle = trim((string) $request->request->get('wealth_style', ''));
            if ($wealthStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            $request->getSession()->set('wealth_style', $wealthStyle);

            $consultingModel = $consultingRegistry->get('wealth_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'wealth_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * PUBLIC SERVICE
         * ==========================
         */
        if ($mode === 'public_service') {
            if ($publicServiceStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            $request->getSession()->set('public_service_style', $publicServiceStyle);

            $consultingModel = $consultingRegistry->get('public_service_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'public_service_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * THINKER
         * ==========================
         */
        if ($mode === 'thinker') {
            if ($thinkerStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            $request->getSession()->set('thinker_style', $thinkerStyle);

            $consultingModel = $consultingRegistry->get('thinker_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'thinker_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * DISC
         * ==========================
         */
        if ($mode === 'disc') {
            if ($discStyle === '') {
                return $this->redirectToRoute('ai_consulting_result');
            }

            $request->getSession()->set('disc_style', $discStyle);

            $consultingModel = $consultingRegistry->get('disc_week');

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'disc_week',
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }


        /**
         * ==========================
         * FREE MODE → USER INTENT
         * ==========================
         */
        if ($mode === 'free') {
            if ($freePrompt === '') {
                // можно flashMessage, если нужно
                return $this->redirectToRoute('ai_consulting_result');
            }

            $consultingModel = $consultingRegistry->get('free');

            // supports() можно использовать для доп.валидации
            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => 'free',
                ]);
            }
            // FreeConsultingService сам прочитает free_prompt из Request
            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * AUTO MODE → JOURNAL REFLECTION
         * ==========================
         */
        if ($mode === 'auto') {
            $consultingModel = $consultingRegistry->pickAuto($user);
            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        /**
         * ==========================
         * MANUAL MODE → MODEL
         * ==========================
         */
        if ($mode === 'manual' && $model) {
            $consultingModel = $consultingRegistry->get($model);

            if (!$consultingModel->supports($user)) {
                return $this->redirectToRoute('consulting_requirements', [
                    'model' => $model,
                ]);
            }

            $consultingModel->generate($user, $lang);

            return $this->redirectToRoute('ai_consulting_result');
        }

        // fallback
        return $this->redirectToRoute('ai_consulting_result');
    }


    /**
     * @Route("/cpanel/ai_consulting/result", name="ai_consulting_result", methods={"GET"})
     */
    public function aiConsultingResult(MbtiPromptProvider $mbtiPromptProvider, DiscPromptProvider $discPromptProvider, ThinkerPromptProvider $thinkerPromptProvider): Response
    {
        $comment = $this->getDoctrine()
            ->getRepository(ConsultingInsight::class)
            ->findLastByUser($this->getUser());

        // Надо посчитать сколько осталось до следующего уровня. Берём все испытания (events) и делим на 10 - min(floor($allEvents / 10), 20),
        $user = $this->getUser();
        $eventRepo = $this->getDoctrine()->getRepository(Event::class);
        $allEvents = $eventRepo->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);
        $nextLevel = ($level + 1) * 10;
        $remaining = $nextLevel - $allEvents;

        $styleDescription = $this->getStyleDescription($comment, $mbtiPromptProvider, $discPromptProvider, $thinkerPromptProvider, $user->getLang());

        return $this->render('cpanel/ai_consulting/result.html.twig', [
            'insight' => $comment,
            'allEvents' => $allEvents,
            'level' => $level,
            'remaining' => $remaining,
            'styleDescription' => $styleDescription,
        ]);
    }

    /**
     * @Route("/cpanel/ai-consulting/show/{id}", requirements={"id"="\d+"}, name="ai_consulting_show", methods={"GET"})
     */
    public function aiConsultingShow(ConsultingInsight $consultingInsight, MbtiPromptProvider $mbtiPromptProvider, DiscPromptProvider $discPromptProvider, ThinkerPromptProvider $thinkerPromptProvider): Response
    {
        // Надо посчитать сколько осталось до следующего уровня. Берём все испытания (events) и делим на 10 - min(floor($allEvents / 10), 20),
        $user = $this->getUser();
        $eventRepo = $this->getDoctrine()->getRepository(Event::class);
        $allEvents = $eventRepo->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);
        $nextLevel = ($level + 1) * 10;
        $remaining = $nextLevel - $allEvents;

        $styleDescription = $this->getStyleDescription($consultingInsight, $mbtiPromptProvider, $discPromptProvider, $thinkerPromptProvider, $user->getLang());

        return $this->render('cpanel/ai_consulting/result.html.twig', [
            'insight' => $consultingInsight,
            'allEvents' => $allEvents,
            'level' => $level,
            'remaining' => $remaining,
            'styleDescription' => $styleDescription,
        ]);
    }

    /**
     * @Route(
     *     "/cpanel/consulting/insight/{id}/add-tasks",
     *     name="consulting_insight_add_tasks",
     *     methods={"POST"}
     * )
     */
    public function addRecommendationsToTasks(
        int $id,
        Security $security,
        ManagerRegistry $doctrine,
        EntityManagerInterface $em,
        AiTaskGenerateService $aiTaskGenerate,
        AiTaskDetailService $aiTaskDetail
    ): Response {
        $user = $security->getUser();

        // --------------------------
        // 1. Insight
        // --------------------------
        $insight = $doctrine
            ->getRepository(ConsultingInsight::class)
            ->find($id);

        if (!$insight || $insight->getUser() !== $user) {
            throw $this->createNotFoundException('Insight not found');
        }

        if ($insight->isGeneratedTasks()) {
            return $this->redirectToRoute('consulting_insight_view', ['id' => $id]);
        }

        $analysis = $insight->getAnalysisJson();

        if (
            empty($analysis['recommendations']) ||
            !is_array($analysis['recommendations'])
        ) {
            return $this->redirectToRoute('consulting_insight_view', ['id' => $id]);
        }

        // --------------------------
        // 2. AI Ensemble (единый)
        // --------------------------
        $ensemble = $doctrine
            ->getRepository(Ensemble::class)
            ->findOneBy([
                'author'      => $user,
                'aiGenerated' => true,
                'draft'       => false,
            ]);

        if (!$ensemble) {
            $ensemble = new Ensemble();
            $ensemble->setAuthor($user);
            $ensemble->setTitle('AI-рекомендованные задачи');
            $ensemble->setAiGenerated(true);
            $ensemble->setDraft(false);
            $ensemble->setPublic(false);
            $ensemble->setCreatedAt(new \DateTimeImmutable());

            $em->persist($ensemble);
        }

        // --------------------------
        // 3. Рекомендации → задачи
        // --------------------------
        foreach ($analysis['recommendations'] as $recommendationText) {

            if (!is_string($recommendationText) || trim($recommendationText) === '') {
                continue;
            }

            // 3.1 Title + idea из рекомендации
            $newTask = $aiTaskGenerate->generateSingleTask(
                $user->getLang(),
                $recommendationText
            );

            // 3.2 Description + instruction
            $details = $aiTaskDetail->generateTaskDetails(
                $newTask['title'],
                $newTask['idea'],
                $user->getLang()
            );

            // 3.3 Event
            $event = new Event();
            $event->setUser($user);
            $event->setTitle($newTask['title']);
            $event->setDescription($details['description']);
            $event->setInstruction($details['instruction']);
            $event->setDate(new \DateTimeImmutable());
            $event->setTime(new \DateTimeImmutable('00:00'));
            $event->setLang($user->getLang());
            $event->setPublic(false);
            $event->setPortfolio(false);
            $event->setTask(true);
            $event->setAiGenerated(true);
            $event->setAiCompleted(false);
            $event->setHidden(false);
            $event->setPriority(0);
            $event->setPower(0);
            $event->setType(1);
            $event->setEnsemble($ensemble);

            $em->persist($event);
        }

        // --------------------------
        // 4. Закрываем инсайт
        // --------------------------
        $insight->setGeneratedTasks(true);
        $insight->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->redirectToRoute('ai_task_open');
    }

    private function getStyleDescription(?ConsultingInsight $insight, MbtiPromptProvider $mbtiPromptProvider, DiscPromptProvider $discPromptProvider, ThinkerPromptProvider $thinkerPromptProvider, string $lang): string
    {
        if (!$insight) {
            return '';
        }

        $analysis = $insight->getAnalysisJson();
        if (!empty($analysis['mbti_style'])) {
            return $mbtiPromptProvider->getStyleDescription($analysis['mbti_style'], $lang);
        }

        if (!empty($analysis['disc_style'])) {
            return $discPromptProvider->getStyleDescription($analysis['disc_style'], $lang);
        }

        if (!empty($analysis['thinker_style'])) {
            return $thinkerPromptProvider->getStyleDescription($analysis['thinker_style'], $lang);
        }

        return '';
    }
}
