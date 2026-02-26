<?php

namespace App\Consulting\Model\Journal;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalWeekComment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JournalReflectionConsultingService implements ConsultingModelInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $deepseekApiKey;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->deepseekApiKey = $deepseekApiKey;
    }

    public function getKey(): string
    {
        return 'journal_reflection';
    }

    public function supports(User $user): bool
    {
        return count($this->getLastWeeks($user)) >= 1;
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $weeks = $this->getLastWeeks($user);

        $aggregation = $this->aggregateLifeBalance($weeks);
        $zones       = $this->classifyBalance($aggregation['avg']);

        $prompt = $this->buildPrompt(
            $user,
            $aggregation,
            $zones
        );

        $rawJson  = $this->fetchJson($prompt);
        $analysis = $this->normalize($rawJson);
        $html     = $this->buildHtml($analysis, $aggregation);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'Месячный анализ завершён');
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

    // --------------------------------------------------
    // DATA
    // --------------------------------------------------

    private function getLastWeeks(User $user): array
    {
        return $this->em
            ->getRepository(JournalWeekComment::class)
            ->createQueryBuilder('w')
            ->andWhere('w.user = :user')
            ->andWhere('w.aiGenerated = true')
            ->setParameter('user', $user)
            ->orderBy('w.weekStart', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function aggregateLifeBalance(array $weeks): array
    {
        $totals = [];
        $counts = [];

        foreach ($weeks as $week) {
            $analysis = $week->getAiAnalysis();

            if (!isset($analysis['life_balance'])) {
                continue;
            }

            foreach ($analysis['life_balance'] as $sphere => $value) {
                $totals[$sphere] = ($totals[$sphere] ?? 0) + (int)$value;
                $counts[$sphere] = ($counts[$sphere] ?? 0) + 1;
            }
        }

        $avg = [];
        foreach ($totals as $sphere => $sum) {
            $avg[$sphere] = round($sum / max(1, $counts[$sphere]), 2);
        }

        return [
            'weeks' => count($weeks),
            'sum'   => $totals,
            'avg'   => $avg,
        ];
    }

    private function classifyBalance(array $avg): array
    {
        $low = [];
        $mid = [];
        $high = [];

        foreach ($avg as $sphere => $value) {
            if ($value <= 2) {
                $low[] = $sphere;
            } elseif ($value >= 4) {
                $high[] = $sphere;
            } else {
                $mid[] = $sphere;
            }
        }

        return compact('low', 'mid', 'high');
    }

    // --------------------------------------------------
    // PROMPT
    // --------------------------------------------------

    private function buildPrompt(
        User $user,
        array $aggregation,
        array $zones
    ): string {
        $avgJson = json_encode($aggregation['avg'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $low     = implode(', ', $zones['low']);
        $high    = implode(', ', $zones['high']);

        return <<<PROMPT
Ты — лайф-коуч и аналитик жизненного баланса.

Ниже — агрегированные данные за {$aggregation['weeks']} недели.
Числа — объективная реальность, НЕ пересчитывай их.

СРЕДНИЕ ОЦЕНКИ (1–5):
{$avgJson}

СЛАБЫЕ СФЕРЫ:
{$low}

СИЛЬНЫЕ СФЕРЫ:
{$high}

ЗАДАЧА:
1) Объясни, что означает такой баланс.
2) Назови главный фокус текущего жизненного этапа.
3) Сформулируй 3 рекомендации (испытания).
4) Сформулируй краткий итог.

ВЕРНИ ТОЛЬКО валидный JSON:
{
  "focus": "",
  "recommendations": [],
  "summary": ""
}

Язык: русский
PROMPT;
    }

    // --------------------------------------------------
    // AI
    // --------------------------------------------------

    private function fetchJson(string $prompt): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Accept'        => 'application/json',
                    ],
                    'json' => [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You output only JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.4,
                        'response_format' => ['type' => 'json_object'],
                    ],
                    'timeout' => 60,
                ]
            );

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? json_decode($content, true) ?? [] : [];

        } catch (\Throwable $e) {
            $this->logger->error('MonthlyReflection error: ' . $e->getMessage());
            return [];
        }
    }

    // --------------------------------------------------
    // NORMALIZE
    // --------------------------------------------------

    private function normalize(array $json): array
    {
        return [
            'focus' => $json['focus'] ?? '',
            'recommendations' => $json['recommendations'] ?? [],
            'summary' => $json['summary'] ?? 'Недостаточно данных для месячного анализа.',
        ];
    }

    // --------------------------------------------------
    // HTML
    // --------------------------------------------------

    private function buildHtml(array $analysis, array $aggregation): string
    {
        $html = '';

        if (!empty($analysis['focus'])) {
            $html .= '<p><strong>Фокус месяца:</strong> ' .
                htmlspecialchars($analysis['focus']) . '</p>';
        }

        if (!empty($analysis['summary'])) {
            $html .= '<p>' . nl2br(htmlspecialchars($analysis['summary'])) . '</p>';
        }

        if (!empty($analysis['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';
            foreach ($analysis['recommendations'] as $rec) {
                $html .= '<li>' . htmlspecialchars($rec) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }
}