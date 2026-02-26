<?php

namespace App\Service\Journal;

use App\Entity\User;
use App\Entity\JournalEntry;
use App\Entity\JournalWeekComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class WeeklyLifeBalanceConsultingService
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

    // ==========================
    // PUBLIC API
    // ==========================

    public function supports(User $user, \DateTimeImmutable $weekStart): bool
    {
        return count($this->getWeekEntries($user, $weekStart)) === 7;
    }

    public function generate(
        JournalWeekComment $weekComment,
        string $lang = 'ru'
    ): void {
        $user      = $weekComment->getUser();
        $weekStart = $weekComment->getWeekStart();

        $entries = $this->getWeekEntries($user, $weekStart);

        if (count($entries) !== 7) {
            throw new \LogicException('Неделя заполнена не полностью');
        }

        $prompt = $this->buildPrompt($entries, $weekStart, $lang);
        $json   = $this->fetchJson($prompt);
        if (empty($json)) {
            throw new \RuntimeException('AI вернул пустой ответ');
        }

        $score = $this->calculateLifeBalanceTotal($json);
        $weekComment->setScore($score);
        $max = 12 * 5;
        $weekComment->setPercent($max > 0 ? (int) round(($score / $max) * 100) : 0);

        $weekComment->setAiAnalysis($json);
        $weekComment->setAiGenerated(true);
        $weekComment->setUpdatedAt(new \DateTimeImmutable());

        $weekComment->setAiAnalysis($json);
        $weekComment->setAiGenerated(true);
        $weekComment->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();
    }

    // ==========================
    // DATA
    // ==========================
    private function getWeekEntries(
        User $user,
        \DateTimeImmutable $weekStart
    ): array {
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

    // ==========================
    // PROMPT
    // ==========================

    private function buildPrompt(
        array $entries,
        \DateTimeImmutable $weekStart,
        string $lang
    ): string {
        $weekEnd = $weekStart->modify('+6 days');

        $entriesText = '';
        foreach ($entries as $entry) {
            $content = str_replace(["\n", "\r"], ' ', (string) $entry->getContent());
            $entriesText .= "[{$entry->getDate()->format('Y-m-d')}] {$content}\n";
        }

        return <<<PROMPT
Ты — аналитик дневниковых записей.
Твоя задача — измерить, насколько каждая сфера жизни
была проявлена в течение недели.

ПЕРИОД:
{$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$entriesText}

СФЕРЫ (12):
Love — романтические отношения
Family — семья и родственники
Friends — друзья и общение
Career — работа и проекты
Money — финансы
Self-Growth — обучение и развитие
Spirituality — ценности и внутренние размышления
Recreation — отдых и восстановление
Environment — дом и окружение
Community — участие в сообществах
Health — здоровье и самочувствие
Appearance — внешний вид и уход за собой

ПРАВИЛА:
- оцени интенсивность и регулярность проявления
- шкала от 1 до 5
- не выдумывай факты
- если сфера почти не упоминалась — ставь низкий балл
- язык: русский

ВЕРНИ ТОЛЬКО валидный JSON:
{
  "life_balance": {
    "Love": 1,
    "Family": 1,
    "Friends": 1,
    "Career": 1,
    "Money": 1,
    "Self-Growth": 1,
    "Spirituality": 1,
    "Recreation": 1,
    "Environment": 1,
    "Community": 1,
    "Health": 1,
    "Appearance": 1
  },
  "summary": "",
  "highlights": [],
  "recommendations": []
}
PROMPT;
    }

    // ==========================
    // AI
    // ==========================

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
                            ['role' => 'system', 'content' => 'You output only valid JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.3,
                        'response_format' => ['type' => 'json_object'],
                    ],
                    'timeout' => 60,
                ]
            );

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            return $content ? json_decode($content, true) : [];

        } catch (\Throwable $e) {
            $this->logger->error('WeeklyLifeBalance AI error: ' . $e->getMessage());
            return [];
        }
    }

    private function calculateLifeBalanceTotal(array $json): int
    {
        if (!isset($json['life_balance']) || !is_array($json['life_balance'])) {
            return 0;
        }

        $total = 0;

        foreach ($json['life_balance'] as $key => $value) {
            // Deepseek может вернуть число строкой — приводим
            if (!is_numeric($value)) {
                continue;
            }

            $v = (int) $value;

            // защита от мусора: шкала 1..5
            if ($v < 1) { $v = 1; }
            if ($v > 5) { $v = 5; }

            $total += $v;
        }

        return $total;
    }
}