<?php

namespace App\Consulting\Model\Journal;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JournalReflectionConsultingServiceOld implements ConsultingModelInterface
{
    private $em;
    private $httpClient;
    private $logger;
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
        return count($this->getEntries($user)) >= 3;
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $entries = $this->getEntries($user);
        $entriesSnapshot = [];

        foreach ($entries as $entry) {
            $entriesSnapshot[] = [
                'date'    => $entry->getDate()->format('Y-m-d'),
                'content' => (string) $entry->getContent(),
            ];
        }

        $prompt = $this->buildPrompt(
            (string) $user->getDescription(),
            $entriesSnapshot
        );

        $rawJson  = $this->fetchJson($prompt);
        $analysis = $this->normalize($rawJson);
        $html     = $this->buildHtml($analysis);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'Анализ завершён');
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

    // --------------------------
    // Data
    // --------------------------

    private function getEntries(User $user): array
    {
        return $this->em
            ->getRepository(JournalEntry::class)
            ->createQueryBuilder('j')
            ->andWhere('j.user = :user')
            ->andWhere('j.content IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('j.date', 'DESC')
            ->setMaxResults(14)
            ->getQuery()
            ->getResult();
    }

    // --------------------------
    // Prompt
    // --------------------------

    private function buildPrompt(string $userDescription, array $entries): string
    {
        $entriesText = '';
        foreach (array_reverse($entries) as $entry) {
            $content = str_replace(["\r", "\n"], ' ', $entry['content']);
            $entriesText .= "[{$entry['date']}] {$content}\n";
        }

        return <<<PROMPT
Ты — аналитик дневниковых записей и лайф-коуч.
Всегда возвращай ТОЛЬКО валидный JSON без markdown и комментариев.

ОПИСАНИЕ ПОЛЬЗОВАТЕЛЯ:
{$userDescription}

ДНЕВНИКОВЫЕ ЗАПИСИ:
{$entriesText}

ЗАДАЧА:
1) Выяви эмоциональный фон и динамику.
2) Найди повторяющиеся темы и паттерны.
3) Определи главный фокус текущего периода.
4) Дай 3–6 коротких рекомендаций на ближайшую неделю.
5) Сформулируй краткий итог.

ФОРМАТ JSON:
{
  "emotional_tone": {
    "baseline": "",
    "trend": "",
    "evidence": []
  },
  "themes": [
    { "name": "", "weight": 1 }
  ],
  "patterns": [
    {
      "pattern": "",
      "trigger": "",
      "effect": ""
    }
  ],
  "focus": {
    "main_focus": "",
    "goal_7_days": ""
  },
  "recommendations": [],
  "summary": ""
}

ПРАВИЛА:
- weight от 1 до 10
- не выдумывай факты
- язык: русский
PROMPT;
    }

    // --------------------------
    // AI
    // --------------------------

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

            if (!$content) {
                return [];
            }

            return json_decode($content, true) ?: [];

        } catch (\Throwable $e) {
            $this->logger->error('JournalReflection error: ' . $e->getMessage());
            return [];
        }
    }

    // --------------------------
    // Normalize
    // --------------------------

    private function normalize(array $json): array
    {
        if (empty($json)) {
            return [
                'emotional_tone' => [],
                'themes' => [],
                'patterns' => [],
                'focus' => [],
                'recommendations' => [],
                'summary' => 'Недостаточно данных для анализа дневника.',
            ];
        }

        return [
            'emotional_tone' => $json['emotional_tone'] ?? [],
            'themes' => $json['themes'] ?? [],
            'patterns' => $json['patterns'] ?? [],
            'focus' => $json['focus'] ?? [],
            'recommendations' => $json['recommendations'] ?? [],
            'summary' => $json['summary'] ?? 'Анализ завершён.',
        ];
    }

    // --------------------------
    // HTML
    // --------------------------

    private function buildHtml(array $data): string
    {
        $html = '';

        if (!empty($data['focus']['main_focus'])) {
            $html .= '<p><strong>Фокус периода:</strong> ' .
                htmlspecialchars($data['focus']['main_focus']) . '</p>';
        }

        if (!empty($data['summary'])) {
            $html .= '<p>' . nl2br(htmlspecialchars($data['summary'])) . '</p>';
        }

        if (!empty($data['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';
            foreach ($data['recommendations'] as $rec) {
                $html .= '<li>' . htmlspecialchars($rec) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }
}