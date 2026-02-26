<?php

namespace App\Consulting\Model\Disc;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\JournalEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class JournalDiscWeekConsultingService implements ConsultingModelInterface
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
        DiscPromptProvider $promptProvider,
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
        return 'disc_week';
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

        $discStyle = $this->readDiscStyleFromSession();
        if ($discStyle === '') {
            return $this->createEmptyInsight($user, 'Не выбрана DISC-линза.', $lang);
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

        // Если режим AUTO -> сначала определяем стиль
        if ($discStyle === 'auto') {
            $entries = $this->getWeekEntries($user, $weekStart);
            $weekText = $this->buildWeekText($entries);

            if (trim($weekText) === '') {
                return $this->createEmptyInsight($user, 'Недостаточно данных: неделя пустая.', $lang);
            }

            // Определяем лучший стиль
            $detected = $this->detectBestStyle($weekText, $lang);
            $discStyle = $detected['best_style'] ?? 'D'; // fallback
        }

        return $this->generateForWeek($user, $weekStart, $discStyle, $lang);
    }

    private function detectBestStyle(string $weekText, string $lang): array
    {
        $system = $this->promptProvider->getAutoDetectionSystemPrompt($lang);
        $user = $this->promptProvider->getAutoDetectionUserPrompt($weekText, $lang);

        $json = $this->fetchJson($system, $user);

        // Валидация
        $style = $json['best_style'] ?? '';
        $allowed = ['D', 'I', 'S', 'C'];
        if (!in_array($style, $allowed, true)) {
            return ['best_style' => 'D', 'reason' => 'Fallback default'];
        }

        return $json;
    }

    private function readDiscStyleFromSession(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request || !$request->hasSession()) {
            return '';
        }

        $session = $request->getSession();
        $style = trim((string)$session->get('disc_style', '')); // Важно: здесь ключ сессии disc_style

        // чтобы не “прилипало” к следующей генерации
        $session->remove('disc_style');

        // валидация
        $allowed = ['D', 'I', 'S', 'C', 'auto'];
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
            'disc_style' => null,
            'rating' => 0,
            'rating_reason' => '',
            'what_liked' => [],
            'missing_notes' => [],
            'small_questions' => [],
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

    public function generateForWeek(User $user, \DateTimeImmutable $weekStart, string $discStyle, string $lang): ConsultingInsight
    {
        set_time_limit(180);

        $entries = $this->getWeekEntries($user, $weekStart);
        $weekText = $this->buildWeekText($entries);

        if (trim($weekText) === '') {
            return $this->createEmptyInsight($user, 'Недостаточно данных: неделя пустая.', $lang);
        }

        $systemPrompt = $this->promptProvider->getSystemPrompt($discStyle, $lang);
        $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $discStyle, $lang);

        $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
        $analysis = $this->normalize($rawJson, $discStyle);

        $styleDescription = $this->promptProvider->getStyleDescription($discStyle, $lang);

        $html = $this->twig->render('consulting/disc/week_analysis.html.twig', [
            'analysis' => $analysis,
            'weekStart' => $weekStart,
            'weekEnd' => $weekStart->modify('+6 days'),
            'styleDescription' => $styleDescription,
        ]);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?? 'DISC-анализ недели завершён');
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

            // защита от слишком длинных записей
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
            $this->logger->info('DiscWeek: Starting API request to Deepseek');

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

            $this->logger->info('DiscWeek: API request completed');

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? (json_decode($content, true) ?? []) : [];
        } catch (\Throwable $e) {
            $this->logger->error('DiscWeek error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    // -----------------------------
    // NORMALIZE
    // -----------------------------

    private function normalize(array $json, string $discStyle): array
    {
        $rating = $json['rating'] ?? 0;

        if (is_string($rating) && preg_match('/-?\d+(\.\d+)?/', $rating, $m)) {
            $rating = $m[0];
        }

        $rating = (float)$rating;
        if ($rating < 0) $rating = 0;
        if ($rating > 10) $rating = 10;

        $asStrings = static function ($v): array {
            if (!is_array($v)) return [];
            $out = [];
            foreach ($v as $item) {
                $s = trim((string)$item);
                if ($s !== '') $out[] = $s;
            }
            return $out;
        };

        $out = [
            'disc_style' => $discStyle,
            'rating' => $rating,
            'rating_reason' => trim((string)($json['rating_reason'] ?? '')),
            'what_liked' => $asStrings($json['what_liked'] ?? null),
            'missing_notes' => $asStrings($json['missing_notes'] ?? null),
            'small_questions' => $asStrings($json['small_questions'] ?? null),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Недостаточно данных для DISC-анализа недели.')),
        ];

        // Лимиты для UI
        $out['what_liked'] = array_slice($out['what_liked'], 0, 3);
        $out['missing_notes'] = array_slice($out['missing_notes'], 0, 3);
        $out['small_questions'] = array_slice($out['small_questions'], 0, 3);
        $out['recommendations'] = array_slice($out['recommendations'], 0, 3);

        return $out;
    }
}
