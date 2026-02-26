<?php

namespace App\Consulting\Model\JournalCareerLoop4;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JournalCareerLoop4WeekConsultingService implements ConsultingModelInterface
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
        return 'career_loop_4_week';
    }

    public function supports(User $user): bool
    {
        return $this->getLatestEntryDate($user) !== null;
    }

    private function getLatestEntryDate(User $user): ?\DateTimeImmutable
    {
        // MAX по дате — самый быстрый и точный способ найти "последнюю заполненную неделю"
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

        // Doctrine обычно возвращает DateTime/DateTimeImmutable — приводим к Immutable
        $maxDate = $row['maxDate'];

        if ($maxDate instanceof \DateTimeImmutable) {
            return $maxDate;
        }

        if ($maxDate instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromMutable(
                $maxDate instanceof \DateTime ? $maxDate : \DateTime::createFromInterface($maxDate)
            );
        }

        // на всякий случай
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
            // Можно и exception, но лучше вернуть понятный инсайт
            return $this->createEmptyInsight($user, 'Недостаточно данных: нет записей дневника.', $lang);
        }

        $weekStart = $this->getWeekStart($latest);

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
            'scores' => [
                'know_yourself' => 0,
                'explore_options' => 0,
                'get_focused' => 0,
                'take_action' => 0,
            ],
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
        $entries = $this->getWeekEntries($user, $weekStart);

        $weekText = $this->buildWeekText($entries, $lang);
        $prompt   = $this->buildPrompt($weekStart, $weekText, $lang);

        $rawJson  = $this->fetchJson($prompt);
        $analysis = $this->normalize($rawJson);
        $html     = $this->buildHtml($analysis, $weekStart);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'Недельный карьерный анализ завершён');
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

    /**
     * Берём понедельник как начало недели.
     */
    private function getWeekStart(\DateTimeImmutable $date): \DateTimeImmutable
    {
        // ISO-8601: 1 = Monday ... 7 = Sunday
        $dow = (int)$date->format('N');
        return $date->modify('-' . ($dow - 1) . ' days')->setTime(0, 0, 0);
    }

    /**
     * ТВОЙ МЕТОД — без изменений.
     */
    private function getWeekEntries(User $user, \DateTimeImmutable $weekStart): array
    {
        $weekEnd = $weekStart->modify('+6 days');

        return $this->em
            ->getRepository(JournalEntry::class)
            ->createQueryBuilder('j')
            ->andWhere('j.user = :user')
            ->andWhere('j.date BETWEEN :start AND :end')
            ->andWhere('j.content IS NOT NULL')
            ->setParameter('user', $user)
            ->setParameter('start', $weekStart)
            ->setParameter('end', $weekEnd)
            ->orderBy('j.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Собираем текст недели для LLM.
     * Важно: уменьшаем шум, но сохраняем хронологию.
     */
    private function buildWeekText(array $entries, string $lang): string
    {
        if (empty($entries)) {
            return '';
        }

        $lines = [];
        foreach ($entries as $entry) {
            /** @var JournalEntry $entry */
            $date = $entry->getDate(); // DateTimeInterface
            $content = trim((string)$entry->getContent());

            if ($content === '') {
                continue;
            }

            // Лёгкая защита от слишком длинных дневников.
            // 1500 символов на день обычно более чем достаточно для анализа недели.
            if (mb_strlen($content) > 1500) {
                $content = mb_substr($content, 0, 1500) . '…';
            }

            $lines[] = $date->format('Y-m-d') . ': ' . $content;
        }

        return implode("\n\n", $lines);
    }

    // --------------------------------------------------
    // PROMPT
    // --------------------------------------------------

    private function buildPrompt(\DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        return <<<PROMPT
Ты — карьерный коуч и аналитик. Ты анализируешь дневник за одну неделю и оцениваешь его по модели из 4 факторов:
1) Know Yourself (Понимание себя)
2) Explore Options (Исследование возможностей)
3) Get Focused (Фокус и выбор)
4) Take Action (Действия)

ПРАВИЛА:
- Опирайся ТОЛЬКО на текст дневника ниже.
- Если чего-то нет в тексте — так и скажи.
- Оценки по каждому фактору ставь по шкале 0–10.
- Дай "signals": короткие факты/цитаты (до 12 слов каждая), на которых основана оценка.
- Дай "gaps": что отсутствует/слабо выражено.
- Дай 3 рекомендации (в формате действий на следующую неделю).
- Дай один главный фокус на следующую неделю (1 предложение).

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК (текст недели):
{$weekText}

ВЕРНИ ТОЛЬКО валидный JSON:
{
  "scores": {
    "know_yourself": 0,
    "explore_options": 0,
    "get_focused": 0,
    "take_action": 0
  },
  "focus_next_week": "",
  "signals": {
    "know_yourself": [],
    "explore_options": [],
    "get_focused": [],
    "take_action": []
  },
  "gaps": {
    "know_yourself": [],
    "explore_options": [],
    "get_focused": [],
    "take_action": []
  },
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

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('CareerLoop4Week error: ' . $e->getMessage());
            return [];
        }
    }

    // --------------------------------------------------
    // NORMALIZE
    // --------------------------------------------------

    private function normalize(array $json): array
    {
        $scores = is_array($json['scores'] ?? null) ? $json['scores'] : [];

        $out = [
            'scores' => [
                'know_yourself' => isset($scores['know_yourself']) ? (float)$scores['know_yourself'] : 0.0,
                'explore_options' => isset($scores['explore_options']) ? (float)$scores['explore_options'] : 0.0,
                'get_focused' => isset($scores['get_focused']) ? (float)$scores['get_focused'] : 0.0,
                'take_action' => isset($scores['take_action']) ? (float)$scores['take_action'] : 0.0,
            ],
            'focus_next_week' => (string)($json['focus_next_week'] ?? ''),
            'signals' => is_array($json['signals'] ?? null) ? $json['signals'] : [],
            'gaps' => is_array($json['gaps'] ?? null) ? $json['gaps'] : [],
            'recommendations' => is_array($json['recommendations'] ?? null) ? $json['recommendations'] : [],
            'summary' => (string)($json['summary'] ?? 'Недостаточно данных для недельного анализа.'),
        ];

        // Безопасно нормализуем signals/gaps до массивов
        foreach (['signals', 'gaps'] as $key) {
            if (!is_array($out[$key])) {
                $out[$key] = [];
            }
            foreach (['know_yourself', 'explore_options', 'get_focused', 'take_action'] as $k) {
                if (!isset($out[$key][$k]) || !is_array($out[$key][$k])) {
                    $out[$key][$k] = [];
                }
            }
        }

        return $out;
    }

    // --------------------------------------------------
    // HTML
    // --------------------------------------------------

    private function buildHtml(array $analysis, \DateTimeImmutable $weekStart): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        $labels = [
            'know_yourself' => 'Know Yourself',
            'explore_options' => 'Explore Options',
            'get_focused' => 'Get Focused',
            'take_action' => 'Take Action',
        ];

        $html = '';
        $html .= '<p><strong>Период:</strong> ' .
            htmlspecialchars($weekStart->format('Y-m-d')) . ' — ' .
            htmlspecialchars($weekEnd->format('Y-m-d')) . '</p>';

        if (!empty($analysis['focus_next_week'])) {
            $html .= '<p><strong>Фокус следующей недели:</strong> ' .
                htmlspecialchars($analysis['focus_next_week']) . '</p>';
        }

        if (!empty($analysis['scores']) && is_array($analysis['scores'])) {
            $html .= '<p><strong>Оценки (0–10):</strong></p><ul>';
            foreach ($labels as $k => $label) {
                $val = $analysis['scores'][$k] ?? 0;
                $html .= '<li>' . htmlspecialchars($label) . ': ' . htmlspecialchars((string)$val) . '</li>';
            }
            $html .= '</ul>';
        }

        if (!empty($analysis['summary'])) {
            $html .= '<p>' . nl2br(htmlspecialchars($analysis['summary'])) . '</p>';
        }

        // Сигналы
        if (!empty($analysis['signals']) && is_array($analysis['signals'])) {
            $html .= '<p><strong>Сигналы из дневника:</strong></p>';
            foreach ($labels as $k => $label) {
                $items = $analysis['signals'][$k] ?? [];
                if (empty($items)) continue;

                $html .= '<p><em>' . htmlspecialchars($label) . ':</em></p><ul>';
                foreach ($items as $s) {
                    $html .= '<li>' . htmlspecialchars((string)$s) . '</li>';
                }
                $html .= '</ul>';
            }
        }

        // Пробелы
        if (!empty($analysis['gaps']) && is_array($analysis['gaps'])) {
            $html .= '<p><strong>Чего не хватало:</strong></p>';
            foreach ($labels as $k => $label) {
                $items = $analysis['gaps'][$k] ?? [];
                if (empty($items)) continue;

                $html .= '<p><em>' . htmlspecialchars($label) . ':</em></p><ul>';
                foreach ($items as $s) {
                    $html .= '<li>' . htmlspecialchars((string)$s) . '</li>';
                }
                $html .= '</ul>';
            }
        }

        if (!empty($analysis['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';
            foreach ($analysis['recommendations'] as $rec) {
                $html .= '<li>' . htmlspecialchars((string)$rec) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }
}