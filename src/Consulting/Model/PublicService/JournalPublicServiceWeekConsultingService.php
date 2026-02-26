<?php

namespace App\Consulting\Model\PublicService;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\User;
use App\Repository\JournalEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;
use DateTimeImmutable;

class JournalPublicServiceWeekConsultingService implements ConsultingModelInterface
{
    private $journalRepo;
    private $httpClient;
    private $twig;
    private $promptProvider;
    private $deepseekApiKey;
    private $em;

    public function __construct(
        JournalEntryRepository $journalRepo,
        HttpClientInterface $httpClient,
        Environment $twig,
        PublicServicePromptProvider $promptProvider,
        string $deepseekApiKey,
        EntityManagerInterface $em
    ) {
        $this->journalRepo = $journalRepo;
        $this->httpClient = $httpClient;
        $this->twig = $twig;
        $this->promptProvider = $promptProvider;
        $this->deepseekApiKey = $deepseekApiKey;
        $this->em = $em;
    }

    public function supports(User $user): bool
    {
        return true;
    }

    public static function getModelKey(): string
    {
        return 'public_service_week';
    }

    public function getKey(): string
    {
        return self::getModelKey();
    }

    private function getLatestEntryDate(User $user): ?\DateTimeImmutable
    {
        $row = $this->em
            ->getRepository(\App\Entity\JournalEntry::class)
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

    private function getWeekStart(\DateTimeImmutable $date): \DateTimeImmutable
    {
        $dow = (int)$date->format('N');
        return $date->modify('-' . ($dow - 1) . ' days')->setTime(0, 0, 0);
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $latest = $this->getLatestEntryDate($user);

        if ($latest === null) {
            return $this->createEmptyInsight('drucker', new DateTimeImmutable('today'), new DateTimeImmutable('today'), $user);
        }

        $session = null;
        if (isset($GLOBALS['request']) && $GLOBALS['request']) {
            $session = $GLOBALS['request']->getSession();
        }

        $style = $this->readStyleFromSession($session);
        if (!$style) {
            $style = 'drucker'; // Default fallback
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

    private function readStyleFromSession($session): ?string
    {
        if (!$session) {
            return null;
        }

        $style = trim((string)$session->get('public_service_style', ''));

        $session->remove('public_service_style');

        $allowed = ['drucker'];
        if (!in_array($style, $allowed, true)) {
            return '';
        }

        return $style;
    }

    public function generateForWeek(User $user, DateTimeImmutable $weekStart, string $style, string $lang): ConsultingInsight
    {
        $weekEnd = $weekStart->modify('+6 days');

        $entries = $this->journalRepo->createQueryBuilder('j')
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

        if (count($entries) === 0) {
            return $this->createEmptyInsight($style, clone $weekStart, clone $weekEnd, clone $user);
        }

        $weekText = $this->buildWeekText($entries);

        if ($style === 'drucker') {
            $systemPrompt = $this->promptProvider->getSystemPrompt($style, $lang);
            $userPrompt = $this->promptProvider->buildUserPrompt($weekStart, $weekText, $style, $lang);

            $rawJson = $this->fetchJson($systemPrompt, $userPrompt);
            $analysis = $this->normalizeDrucker($rawJson, $style);
        } else {
            $analysis = [
                'public_service_style' => $style,
                'summary' => 'Анализ для этого автора пока в разработке.',
                'rating' => 0,
            ];
        }

        $html = $this->twig->render('consulting/public_service/week_analysis.html.twig', [
            'analysis' => $analysis,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'styleDescription' => $this->promptProvider->getStyleDescription($style, $lang),
            'lang' => $lang,
        ]);

        $insight = new ConsultingInsight();
        $managedUser = $this->em->getRepository(User::class)->find($user->getId());
        $insight->setUser($managedUser ?: $user);
        $insight->setModel(self::getModelKey());
        $insight->setSummary($analysis['summary'] ?? 'Нет данных.');
        $insight->setHtml($html);
        $insight->setAnalysisJson([
            'rating' => $analysis['rating'] ?? 0,
            'public_service_style' => $style,
            'entries_count' => count($entries),
            'language' => $lang,
            'analysisDate' => clone $weekStart,
        ]);
        $insight->setGeneratedTasks(false);

        $now = new DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    private function buildWeekText(array $entries): string
    {
        $text = '';
        foreach ($entries as $entry) {
            $dateStr = $entry->getDate() ? $entry->getDate()->format('Y-m-d') : 'Бездаты';
            $content = trim($entry->getContent() ?? '');
            if ($content !== '') {
                $text .= "--- ЗАПИСЬ ОТ {$dateStr} ---\n{$content}\n\n";
            }
        }
        return $text;
    }

    private function fetchJson(string $systemPrompt, string $userPrompt): array
    {
        try {
            $response = $this->httpClient->request('POST', 'https://api.deepseek.com/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.3,
                ],
                'timeout' => 60,
            ]);

            $data = $response->toArray();
            $content = $data['choices'][0]['message']['content'] ?? '{}';
            $decoded = json_decode($content, true);

            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            return [
                'error' => "Ошибка API: " . $e->getMessage(),
            ];
        }
    }

    private function createEmptyInsight(string $style, DateTimeImmutable $weekStart, DateTimeImmutable $weekEnd, User $user): ConsultingInsight
    {
        $html = $this->twig->render('consulting/public_service/week_analysis.html.twig', [
            'analysis' => [
                'public_service_style' => $style,
                'summary' => 'Нет записей за эту неделю.',
                'rating' => 0,
                'effectiveness_score' => 0,
            ],
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);

        $insight = new ConsultingInsight();
        $managedUser = $this->em->getRepository(User::class)->find($user->getId());
        $insight->setUser($managedUser ?: $user);
        $insight->setModel(self::getModelKey());
        $insight->setSummary('Нет записей за эту неделю.');
        $insight->setHtml($html);
        $insight->setAnalysisJson([
            'rating' => 0,
            'public_service_style' => $style,
            'entries_count' => 0,
            'analysisDate' => clone $weekStart,
        ]);
        $insight->setGeneratedTasks(false);

        $now = new DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    private function normalizeDrucker(array $json, string $style): array
    {
        $score = $json['effectiveness_score'] ?? 0;
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

        $metrics = $json['drucker_metrics'] ?? [];
        $normalizedMetrics = [
            'mission_clarity' => min(100, max(0, (int)($metrics['mission_clarity'] ?? 0))),
            'results_focus' => min(100, max(0, (int)($metrics['results_focus'] ?? 0))),
            'team_and_volunteers' => min(100, max(0, (int)($metrics['team_and_volunteers'] ?? 0))),
            'innovation_and_marketing' => min(100, max(0, (int)($metrics['innovation_and_marketing'] ?? 0))),
        ];

        return [
            'public_service_style' => $style,
            'rating' => $score,
            'effectiveness_score' => $score,
            'drucker_metrics' => $normalizedMetrics,
            'leadership_insight' => trim((string)($json['leadership_insight'] ?? 'Нет данных')),
            'resource_allocation' => trim((string)($json['resource_allocation'] ?? 'Нет данных')),
            'recommendations' => $asStrings($json['recommendations'] ?? null),
            'summary' => trim((string)($json['summary'] ?? 'Нет данных.')),
        ];
    }
}
