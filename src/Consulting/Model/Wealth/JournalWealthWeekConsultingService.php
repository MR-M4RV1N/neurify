<?php

namespace App\Consulting\Model\Wealth;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class JournalWealthWeekConsultingService implements ConsultingModelInterface
{
    private $em;
    private $httpClient;
    private $logger;
    private $requestStack;
    private $promptProvider;
    private $twig;
    private $deepseekApiKey;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        RequestStack $requestStack,
        WealthPromptProvider $promptProvider,
        Environment $twig,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->promptProvider = $promptProvider;
        $this->twig = $twig;
        $this->deepseekApiKey = $deepseekApiKey;
    }

    public function getKey(): string
    {
        return 'wealth_week';
    }

    public function supports(User $user): bool
    {
        return $this->getLatestEntryDate($user) !== null;
    }

    private function getLatestEntryDate(User $user): ?\DateTimeImmutable
    {
        $row = $this->em
            ->getRepository(JournalEntry::class)
            ->createQueryBuilder('j')
            ->select('MAX(j.date) AS maxDate')
            ->andWhere('j.user = :user')
            ->andWhere('j.content IS NOT NULL')
            ->andWhere('j.content != :empty')
            ->setParameter('user', $user)
            ->setParameter('empty', '')
            ->getQuery()
            ->getOneOrNullResult();

        if (!$row || empty($row['maxDate'])) {
            return null;
        }

        $maxDate = $row['maxDate'];

        if ($maxDate instanceof \DateTimeImmutable) {
            return $maxDate;
        }

        if ($maxDate instanceof \DateTimeInterface) {
            return new \DateTimeImmutable($maxDate->format('Y-m-d H:i:s'));
        }

        try {
            return new \DateTimeImmutable((string)$maxDate);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $latest = $this->getLatestEntryDate($user);

        if ($latest === null) {
            return $this->createEmptyInsight($user, 'Недостаточно данных: нет записей дневника.', $lang);
        }

        $style = $this->readStyleFromSession();
        if ($style === '') {
            return $this->createEmptyInsight($user, 'Не выбран автор (Wealth & Success).', $lang);
        }

        $latestWeekStart = $this->getWeekStart($latest);
        $currentWeekStart = $this->getWeekStart(new \DateTimeImmutable());

        if ($latestWeekStart < $currentWeekStart) {
            $weekStart = $latestWeekStart;
        } else {
            $weekStart = $latestWeekStart->modify('-1 week');
        }

        return $this->generateForWeek($user, $weekStart, $style, $lang);
    }

    private function readStyleFromSession(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request || !$request->hasSession()) {
            return '';
        }

        $session = $request->getSession();
        $style = trim((string)$session->get('wealth_style', ''));

        $session->remove('wealth_style');

        $allowed = ['hill', 'clason']; // Can add more like 'kiyosaki' later
        if (!in_array($style, $allowed, true)) {
            return '';
        }

        return $style;
    }

    private function createEmptyInsight(User $user, string $message, string $lang): ConsultingInsight
    {
        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($message);
        $insight->setAnalysisJson([
            'summary' => $message,
            'wealth_style' => null,
            'rating' => 0,
            'recommendations' => [],
        ]);
        $insight->setEventsSnapshot(null);
        $insight->setGeneratedTasks(false);

        $now = new \DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);
        $insight->setHtml('<p>' . htmlspecialchars($message) . '</p>');

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    public function generateForWeek(User $user, \DateTimeImmutable $weekStart, string $style, string $lang): ConsultingInsight
    {
        set_time_limit(180);

        $entries = $this->getWeekEntries($user, $weekStart);
        $weekText = $this->buildWeekText($entries);

        if (trim($weekText) === '') {
            return $this->createEmptyInsight($user, 'Недостаточно данных: неделя пустая.', $lang);
        }

        if ($style === 'hill') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeHill($rawJson, $style);
        } elseif ($style === 'clason') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeClason($rawJson, $style);
        } else {
            $analysis = [
                'wealth_style' => $style,
                'summary' => 'Анализ для этого автора пока в разработке.',
                'rating' => 0,
            ];
        }

        $styleDescription = $this->promptProvider->getStyleDescription($style, $lang);

        $html = $this->twig->render('consulting/wealth/week_analysis.html.twig', [
            'analysis' => $analysis,
            'weekStart' => $weekStart,
            'weekEnd' => $weekStart->modify('+6 days'),
            'styleDescription' => $styleDescription,
        ]);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'Анализ завершен');
        $insight->setAnalysisJson($analysis);
        $insight->setEventsSnapshot(null);
        $insight->setGeneratedTasks(false);

        $now = new \DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);
        $insight->setHtml($html);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    private function getWeekStart(\DateTimeImmutable $date): \DateTimeImmutable
    {
        $dow = (int)$date->format('N');
        return $date->modify('-' . ($dow - 1) . ' days')->setTime(0, 0, 0);
    }

    private function getWeekEntries(User $user, \DateTimeImmutable $weekStart): array
    {
        $weekEnd = $weekStart->modify('+6 days');

        return $this->em
            ->getRepository(JournalEntry::class)
            ->createQueryBuilder('j')
            ->andWhere('j.user = :user')
            ->andWhere('j.date BETWEEN :start AND :end')
            ->andWhere('j.content IS NOT NULL')
            ->andWhere('j.content != :empty')
            ->setParameter('user', $user)
            ->setParameter('start', $weekStart)
            ->setParameter('end', $weekEnd)
            ->setParameter('empty', '')
            ->orderBy('j.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function buildWeekText(array $entries): string
    {
        if (empty($entries)) {
            return '';
        }

        $lines = [];
        foreach ($entries as $entry) {
            $date = $entry->getDate();
            $content = trim((string)$entry->getContent());

            if ($content === '') {
                continue;
            }

            if (mb_strlen($content) > 1500) {
                $content = mb_substr($content, 0, 1500) . '…';
            }

            $lines[] = $date->format('Y-m-d') . ': ' . $content;
        }

        return implode("\n\n", $lines);
    }

    private function fetchJson(string $systemPrompt, string $userPrompt): array
    {
        try {
            $this->logger->info('WealthWeek: Starting API request to Deepseek');

            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Accept'        => 'application/json',
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $userPrompt],
                        ],
                        'temperature' => 0.4,
                        'response_format' => ['type' => 'json_object'],
                    ],
                    'timeout' => 120,
                ]
            );

            $this->logger->info('WealthWeek: API request completed');

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('WealthWeek error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    private function normalizeHill(array $json, string $style): array
    {
        $score = $json['determination_score'] ?? 0;
        if ($score < 0) $score = 0;
        if ($score > 10) $score = 10;

        $asStrings = static function ($v): array {
            if (!is_array($v)) return [];
            $out = [];
            foreach ($v as $item) {
                if (is_array($item) || is_object($item)) {
                    continue;
                }
                $s = trim((string)$item);
                if ($s !== '') $out[] = $s;
            }
            return $out;
        };

        // Goal Alignment
        $ga = $json['goal_alignment'] ?? [];
        $aligned = max(0, (int)($ga['aligned'] ?? 0));
        $drift  = max(0, (int)($ga['drift'] ?? 0));

        $sum = $aligned + $drift;
        if ($sum > 0) {
            $aligned = (int)round(($aligned / $sum) * 100);
            $drift = 100 - $aligned;
        } else {
            $aligned = 50;
            $drift = 50;
        }

        // Principles
        $pr = $json['principles'] ?? [];
        $principles = [
            'faith' => min(100, max(0, (int)($pr['faith'] ?? 0))),
            'auto_suggestion' => min(100, max(0, (int)($pr['auto_suggestion'] ?? 0))),
            'specialized_knowledge' => min(100, max(0, (int)($pr['specialized_knowledge'] ?? 0))),
            'imagination' => min(100, max(0, (int)($pr['imagination'] ?? 0))),
        ];

        $mastermind = trim((string)($json['mastermind_status'] ?? 'Нет данных'));

        return [
            'wealth_style' => $style,
            'rating' => $score, // Rating = Determination Score
            'determination_score' => $score,
            'goal_alignment' => [
                'aligned' => $aligned,
                'drift' => $drift,
            ],
            'principles' => $principles,
            'mastermind_status' => $mastermind,
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }

    private function normalizeClason(array $json, string $style): array
    {
        $score = $json['wealth_building_score'] ?? 0;
        if ($score < 0) $score = 0;
        if ($score > 10) $score = 10;

        $asStrings = static function ($v): array {
            if (!is_array($v)) return [];
            $out = [];
            foreach ($v as $item) {
                if (is_array($item) || is_object($item)) {
                    continue;
                }
                $s = trim((string)$item);
                if ($s !== '') $out[] = $s;
            }
            return $out;
        };

        $cures = $json['cures_assessment'] ?? [];
        $curesAssessment = [
            'pay_yourself_first' => min(100, max(0, (int)($cures['pay_yourself_first'] ?? 0))),
            'control_expenses' => min(100, max(0, (int)($cures['control_expenses'] ?? 0))),
            'multiply_gold' => min(100, max(0, (int)($cures['multiply_gold'] ?? 0))),
            'increase_ability' => min(100, max(0, (int)($cures['increase_ability'] ?? 0))),
        ];

        return [
            'wealth_style' => $style,
            'rating' => $score,
            'wealth_building_score' => $score,
            'cures_assessment' => $curesAssessment,
            'work_attitude' => trim((string)($json['work_attitude'] ?? 'Нет данных')),
            'debt_status' => trim((string)($json['debt_status'] ?? 'Нет данных')),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }
}
