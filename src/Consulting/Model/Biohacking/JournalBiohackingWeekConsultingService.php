<?php

namespace App\Consulting\Model\Biohacking;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class JournalBiohackingWeekConsultingService implements ConsultingModelInterface
{
    private $em;
    private $httpClient;
    private $logger;
    private $promptProvider;
    private $twig;
    private $deepseekApiKey;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        BiohackingPromptProvider $promptProvider,
        Environment $twig,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->promptProvider = $promptProvider;
        $this->twig = $twig;
        $this->deepseekApiKey = $deepseekApiKey;
    }

    public function getKey(): string
    {
        return 'biohacking_week';
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

        $latestWeekStart = $this->getWeekStart($latest);
        $currentWeekStart = $this->getWeekStart(new \DateTimeImmutable());

        if ($latestWeekStart < $currentWeekStart) {
            $weekStart = $latestWeekStart;
        } else {
            $weekStart = $latestWeekStart->modify('-1 week');
        }

        return $this->generateForWeek($user, $weekStart, $lang);
    }

    private function createEmptyInsight(User $user, string $message, string $lang): ConsultingInsight
    {
        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($message);
        $insight->setAnalysisJson([
            'summary' => $message,
            'biohacking_mode' => 'empty',
            'overall_score' => 0,
            'pillars' => [],
            'key_observations' => [],
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

    public function generateForWeek(User $user, \DateTimeImmutable $weekStart, string $lang): ConsultingInsight
    {
        set_time_limit(180);

        $entries = $this->getWeekEntries($user, $weekStart);
        $weekText = $this->buildWeekText($entries);

        if (trim($weekText) === '') {
            return $this->createEmptyInsight($user, 'Недостаточно данных: неделя пустая.', $lang);
        }

        $systemPrompt = $this->promptProvider->getSystemPrompt($lang);
        $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $lang);

        $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
        $analysis = $this->normalizeJson($rawJson);

        $weekEnd = $weekStart->modify('+6 days');

        $html = $this->twig->render('consulting/biohacking/week_analysis.html.twig', [
            'analysis' => $analysis,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'Biohacking analysis completed');
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
            $this->logger->info('BiohackingWeek: Starting API request to Deepseek');

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

            $this->logger->info('BiohackingWeek: API request completed');

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('BiohackingWeek error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    private function normalizeJson(array $json): array
    {
        $overallScore = $json['overall_score'] ?? 0;
        if ($overallScore < 0) $overallScore = 0;
        if ($overallScore > 100) $overallScore = 100;

        $asStrings = static function ($v): array {
            if (!is_array($v)) return [];
            $out = [];
            foreach ($v as $item) {
                if (is_array($item) || is_object($item)) continue;
                $s = trim((string)$item);
                if ($s !== '') $out[] = $s;
            }
            return $out;
        };

        $normalizePillar = function (?array $data) {
            $data = $data ?: [];
            $score = $data['score'] ?? 0;
            if ($score < 0) $score = 0;
            if ($score > 100) $score = 100;
            return [
                'score' => $score,
                'analysis' => trim((string)($data['analysis'] ?? 'Нет данных'))
            ];
        };

        $pillars = $json['pillars'] ?? [];

        return [
            'biohacking_mode' => $json['biohacking_mode'] ?? 'sovijarvi',
            'overall_score' => $overallScore,
            'pillars' => [
                'sleep' => $normalizePillar($pillars['sleep'] ?? null),
                'nutrition' => $normalizePillar($pillars['nutrition'] ?? null),
                'activity' => $normalizePillar($pillars['activity'] ?? null),
                'mind_state' => $normalizePillar($pillars['mind_state'] ?? null),
            ],
            'key_observations' => $asStrings($json['key_observations'] ?? null),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }
}
