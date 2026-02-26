<?php

namespace App\Consulting\Model\Running;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class JournalRunningJapanConsultingService implements ConsultingModelInterface
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
        RunningPromptProvider $promptProvider,
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
        return 'running_japan';
    }

    public function supports(User $user): bool
    {
        // For simplicity, running is always supported if they use the app. 
        // We could restrict to users with at least one journal entry if needed.
        return true;
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        set_time_limit(180);

        // 1. Get week entries
        $currentWeekStart = $this->getWeekStart(new \DateTimeImmutable());
        $entries = $this->getWeekEntries($user, $currentWeekStart);
        $weekText = $this->buildWeekText($entries);

        // 2. Get previous total distance
        $previousTotalDistance = $this->getPreviousTotalDistance($user);

        // 3. Prompt AI
        $systemPrompt = $this->promptProvider->getSystemPrompt($lang);
        $userPrompt = $this->promptProvider->buildUserPrompt($weekText, $previousTotalDistance, $lang);

        $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
        $analysis = $this->normalizeResult($rawJson, $previousTotalDistance);

        // 4. Render HTML
        $html = $this->twig->render('consulting/running/week_analysis_japan.html.twig', [
            'analysis' => $analysis,
            'weekStart' => $currentWeekStart,
            'weekEnd' => $currentWeekStart->modify('+6 days'),
        ]);

        // 5. Save Insight
        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary(mb_substr($analysis['story'] ?? 'Анализ завершен', 0, 100) . '...');
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

    private function getPreviousTotalDistance(User $user): int
    {
        /** @var ConsultingInsight|null $lastInsight */
        $lastInsight = $this->em->getRepository(ConsultingInsight::class)
            ->createQueryBuilder('c')
            ->where('c.user = :user')
            ->andWhere('c.model = :model')
            ->setParameter('user', $user)
            ->setParameter('model', $this->getKey())
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$lastInsight) {
            return 0;
        }

        $json = $lastInsight->getAnalysisJson();
        return (int) ($json['total_distance'] ?? 0);
    }

    private function getWeekStart(\DateTimeImmutable $date): \DateTimeImmutable
    {
        $dow = (int)$date->format('N'); // 1..7
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
            /** @var JournalEntry $entry */
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
            $this->logger->info('RunningJapan: Starting API request to Deepseek');

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
                        'temperature' => 0.6,
                        'response_format' => ['type' => 'json_object'],
                    ],
                    'timeout' => 120,
                ]
            );

            $this->logger->info('RunningJapan: API request completed');

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('RunningJapan error: ' . $e->getMessage());
            return [];
        }
    }

    private function normalizeResult(array $json, int $previousTotal): array
    {
        $weekDistance = max(0, (int) ($json['week_distance'] ?? 0));

        // Prevent AI from calculating total distance drastically wrong
        $calculatedTotal = $previousTotal + $weekDistance;
        $aiTotal = (int) ($json['total_distance'] ?? $calculatedTotal);

        // Trust calculation over AI math if wildly different
        $totalDistance = abs($calculatedTotal - $aiTotal) > 10 ? $calculatedTotal : $aiTotal;

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

        return [
            'week_distance' => $weekDistance,
            'total_distance' => $totalDistance,
            'current_location' => trim((string)($json['current_location'] ?? 'В пути')),
            'next_destination' => trim((string)($json['next_destination'] ?? 'Неизвестно')),
            'distance_to_next' => max(0, (int)($json['distance_to_next'] ?? 0)),
            'progress_percent' => min(100, max(0, (int)($json['progress_percent'] ?? 0))),
            'story' => trim((string)($json['story'] ?? 'Вам нужно пробежать хотя бы километр на этой неделе, чтобы продвинуться по маршруту!')),
            'recommendations' => $asStrings($json['recommendations'] ?? []),
        ];
    }
}
