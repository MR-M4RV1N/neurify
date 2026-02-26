<?php

namespace App\Consulting\Model\Wheel;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WheelConsultingService implements ConsultingModelInterface
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

    public function getKey(): string { return 'life_balance'; }

    public function supports(User $user): bool
    {
        return count($this->getEvents($user)) >= 3;
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $events = $this->getEvents($user);
        $eventsSnapshot = [];

        foreach ($events as $event) {
            $eventsSnapshot[] = [
                'title'       => (string)$event->getTitle(),
                'description' => (string)$event->getDescription(),
                'date'        => $event->getDate() ? $event->getDate()->format('Y-m-d') : null,
            ];
        }

        $prompt = $this->buildPrompt($eventsSnapshot);
        $rawJson = $this->fetchJson($prompt);

        $analysis = $this->normalize($rawJson);
        $html = $this->buildHtml($analysis);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?: 'Анализ завершен');
        $insight->setAnalysisJson($analysis);
        $insight->setEventsSnapshot($eventsSnapshot);
        $insight->setGeneratedTasks(false);
        $insight->setHtml($html);

        $now = new \DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    private function getEvents(User $user): array
    {
        return $this->em
            ->getRepository(Event::class)
            ->getLastMonthEventsByUser($user);
    }

    private function buildPrompt(array $events): string
    {
        $text = '';
        foreach ($events as $e) {
            $title = str_replace(["\r", "\n"], ' ', $e['title']);
            $text .= "- " . $title . "\n";
        }

        // Мы добавляем инструкцию "json" прямо в промпт — это критично для некоторых моделей
        return <<<PROMPT
Ты — эксперт по психологии и лайф-коучингу. Проанализируй список событий пользователя:
{$text}

Верни результат в формате JSON.
Структура:
{
  "events": [{"title": "название", "category": "сектор", "reason": "почему"}],
  "focus": "главный фокус месяца",
  "recommendations": ["совет 1", "совет 2"],
  "summary": "краткий итог"
}

Сектора: Здоровье, Карьера, Финансы, Отношения, Рост, Отдых, Окружение, Творчество, Смысл.
PROMPT;
    }

    private function fetchJson(string $prompt): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a helpful assistant that outputs JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.5,
                        'response_format' => ['type' => 'json_object']
                    ],
                    'timeout' => 60, // Увеличили таймаут
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $this->logger->error("DeepSeek API error. Status: $statusCode");
                return [];
            }

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            if (!$content) {
                return [];
            }

            $cleanJson = $this->extractJson($content);
            $decoded = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->error("JSON Decode Error: " . json_last_error_msg(), ['content' => $content]);
                return [];
            }

            return $decoded ?: [];

        } catch (\Throwable $e) {
            $this->logger->error("Wheel Service Exception: " . $e->getMessage());
            return [];
        }
    }

    private function extractJson(string $text): string
    {
        // Убираем возможные артефакты Markdown
        $text = preg_replace('/^```json\s+/i', '', $text);
        $text = preg_replace('/```$/', '', $text);
        $text = trim($text);

        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start !== false && $end !== false) {
            return substr($text, $start, $end - $start + 1);
        }

        return $text;
    }

    private function normalize(array $json): array
    {
        // Если пришел пустой массив, сразу возвращаем структуру с ошибкой
        if (empty($json)) {
            return [
                'events' => [],
                'focus' => 'Ошибка анализа',
                'recommendations' => [],
                'summary' => 'Не удалось получить ответ от нейросети. Попробуйте позже.',
            ];
        }

        return [
            'events' => $json['events'] ?? [],
            'focus' => $json['focus'] ?? 'Баланс жизненных сфер',
            'recommendations' => $json['recommendations'] ?? [],
            'summary' => $json['summary'] ?? 'Анализ завершен успешно.',
        ];
    }

    private function buildHtml(array $data): string
    {
        // Ошибка / fallback
        if (
            empty($data['events']) &&
            ($data['summary'] === 'Не удалось получить ответ от нейросети. Попробуйте позже.')
        ) {
            return '<div class="alert alert-warning">Сервис временно недоступен.</div>';
        }

        $html = '';

        // ======================
        // Фокус
        // ======================
        if (!empty($data['focus'])) {
            $html .= '<p><strong>Фокус месяца:</strong> ' .
                htmlspecialchars($data['focus'], ENT_QUOTES, 'UTF-8') .
                '</p>';
        }

        // ======================
        // Summary (пояснение)
        // ======================
        if (!empty($data['summary'])) {
            $html .= '<p>' .
                nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) .
                '</p>';
        }

        // ======================
        // Сигналы (события по сферам)
        // ======================
        if (!empty($data['events'])) {

            // Группируем по категориям
            $byCategory = [];

            foreach ($data['events'] as $event) {
                $cat = $event['category'] ?? 'Разное';
                $byCategory[$cat][] = $event['title'] ?? '';
            }

            $html .= '<p><strong>Сигналы жизненного баланса:</strong></p>';

            foreach ($byCategory as $category => $titles) {
                $html .= '<p class="mb-1"><em>' .
                    htmlspecialchars($category, ENT_QUOTES, 'UTF-8') .
                    ':</em></p>';
                $html .= '<ul>';

                foreach ($titles as $title) {
                    $html .= '<li>' .
                        htmlspecialchars($title, ENT_QUOTES, 'UTF-8') .
                        '</li>';
                }

                $html .= '</ul>';
            }
        }

        // ======================
        // Рекомендации
        // ======================
        if (!empty($data['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';

            foreach ($data['recommendations'] as $rec) {
                $html .= '<li>' .
                    htmlspecialchars($rec, ENT_QUOTES, 'UTF-8') .
                    '</li>';
            }

            $html .= '</ul>';
        }

        return $html;
    }
}