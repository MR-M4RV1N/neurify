<?php

namespace App\Consulting\Model\Thinker;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class JournalThinkerWeekConsultingService implements ConsultingModelInterface
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
        ThinkerPromptProvider $promptProvider,
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
        return 'thinker_week';
    }

    public function supports(User $user): bool
    {
        return $this->getLatestEntryDate($user) !== null;
    }

    // -----------------------------
    // ENTRY DATE
    // -----------------------------

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

    // -----------------------------
    // MAIN
    // -----------------------------

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $latest = $this->getLatestEntryDate($user);

        if ($latest === null) {
            return $this->createEmptyInsight($user, 'Недостаточно данных: нет записей дневника.', $lang);
        }

        $style = $this->readStyleFromSession();
        if ($style === '') {
            return $this->createEmptyInsight($user, 'Не выбран мыслитель (Thinker).', $lang);
        }

        // Если последняя запись была на прошлой (или более ранней) неделе — анализируем её.
        // Если последняя запись на текущей неделе — берём предыдущую.
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
        $style = trim((string)$session->get('thinker_style', ''));

        // Очищаем сессию, чтобы не влияло на будущие запросы
        $session->remove('thinker_style');

        // Валидация доступных стилей
        $allowed = ['berne', 'taleb', 'jung'];
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
            'thinker_style' => null,
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

        // Пока реальная логика только для Берна
        if ($style === 'berne') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeBerne($rawJson, $style);
        } elseif ($style === 'taleb') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeTaleb($rawJson, $style);
        } elseif ($style === 'jung') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeJung($rawJson, $style);
        } else {
            // Заглушка
            $analysis = [
                'thinker_style' => $style,
                'summary' => 'Анализ для этого мыслителя пока в разработке.',
                'rating' => 0,
            ];
        }

        $styleDescription = $this->promptProvider->getStyleDescription($style, $lang);

        $html = $this->twig->render('consulting/thinker/week_analysis.html.twig', [
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

    // -----------------------------
    // DATA
    // -----------------------------

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

    // -----------------------------
    // AI
    // -----------------------------

    private function fetchJson(string $systemPrompt, string $userPrompt): array
    {
        try {
            $this->logger->info('ThinkerWeek: Starting API request to Deepseek');

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

            $this->logger->info('ThinkerWeek: API request completed');

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('ThinkerWeek error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    // -----------------------------
    // NORMALIZE
    // -----------------------------

    private function normalizeBerne(array $json, string $style): array
    {
        $rating = $json['rating'] ?? 0;
        if ($rating < 0) $rating = 0;
        if ($rating > 10) $rating = 10;

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

        // Нормализация эго-состояний (сумма должна быть 100)
        $ego = $json['ego_states'] ?? [];
        $p = max(0, (int)($ego['parent'] ?? 0));
        $a = max(0, (int)($ego['adult'] ?? 0));
        $c = max(0, (int)($ego['child'] ?? 0));

        $sum = $p + $a + $c;
        if ($sum > 0) {
            $parent = (int)round(($p / $sum) * 100);
            $adult  = (int)round(($a / $sum) * 100);
            $child  = 100 - $parent - $adult; // Гарантируем 100
        } else {
            // Если всё нули, даем равные доли или заглушку
            $parent = 33;
            $adult  = 34;
            $child  = 33;
        }

        return [
            'thinker_style' => $style,
            'rating' => $rating,
            'ego_states' => [
                'parent' => $parent,
                'adult' => $adult,
                'child' => $child,
            ],
            'games_played' => $asStrings($json['games_played'] ?? null),
            'key_transactions' => $asStrings($json['key_transactions'] ?? null),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }

    private function normalizeTaleb(array $json, string $style): array
    {
        $score = $json['antifragility_score'] ?? 0;
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

        // Barbell Strategy
        $bb = $json['barbell_strategy'] ?? [];
        $cons = max(0, (int)($bb['conservative'] ?? 0));
        $agg  = max(0, (int)($bb['aggressive'] ?? 0));
        $mid  = max(0, (int)($bb['middle'] ?? 0));
        $bbSum = $cons + $agg + $mid;

        if ($bbSum > 0) {
            $cons = (int)round(($cons / $bbSum) * 100);
            $agg  = (int)round(($agg / $bbSum) * 100);
            $mid  = 100 - $cons - $agg;
        } else {
            $cons = 40;
            $agg = 40;
            $mid = 20;
        }

        // Metrics
        $mt = $json['metrics'] ?? [];
        $metrics = [
            'via_negativa' => min(100, max(0, (int)($mt['via_negativa'] ?? 0))),
            'skin_in_the_game' => min(100, max(0, (int)($mt['skin_in_the_game'] ?? 0))),
            'optionality' => min(100, max(0, (int)($mt['optionality'] ?? 0))),
        ];

        return [
            'thinker_style' => $style,
            'rating' => $score, // General Rating = Antifragility Score
            'antifragility_score' => $score,
            'barbell' => [
                'conservative' => $cons,
                'aggressive' => $agg,
                'middle' => $mid,
            ],
            'metrics' => $metrics,
            'black_swans' => $asStrings($json['black_swans'] ?? null),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }


    private function normalizeJung(array $json, string $style): array
    {
        $score = $json['individuation_level'] ?? 0;
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

        // Shadow Analysis
        $sh = $json['shadow_analysis'] ?? [];
        $shadow = [
            'score' => min(100, max(0, (int)($sh['score'] ?? 0))),
            'description' => trim((string)($sh['description'] ?? 'Нет данных')),
        ];

        // Balance Inner/Outer
        $bl = $json['balance'] ?? [];
        $inner = max(0, (int)($bl['inner_world'] ?? 0));
        $outer = max(0, (int)($bl['outer_world'] ?? 0));
        $sum = $inner + $outer;
        if ($sum > 0) {
            $inner = (int)round(($inner / $sum) * 100);
            $outer = 100 - $inner;
        } else {
            $inner = 50;
            $outer = 50;
        }

        return [
            'thinker_style' => $style,
            'rating' => $score, // Rating = Individuation Level
            'individuation_level' => $score,
            'shadow_analysis' => $shadow,
            'archetypes' => $json['archetypes'] ?? [], // Array of objects {name, description}
            'balance' => [
                'inner_world' => $inner,
                'outer_world' => $outer,
            ],
            'synchronicity' => $json['synchronicity'] ?? null,
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }
}
