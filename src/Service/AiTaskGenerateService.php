<?php

namespace App\Service;

use App\Entity\Swot;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiTaskGenerateService
{
    private $http;
    private $apiKey;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->apiKey = $deepseekApiKey;
    }

    /**
     * Генерирует:
     * 1) короткий комментарий (<500 chars)
     * 2) 6 задач в виде: title + idea
     */
    public function generateTaskTitles(
        string $notes,
        string $lang,
        string $artisan,
        string $profileDescription,
        ?Swot $swot,
        string $strategy = 'career'
    ): array {
        // ---------- ОГРАНИЧЕНИЕ ДЛИНЫ ----------
        $artisan            = mb_substr(trim($artisan), 0, 300);
        $profileDescription = mb_substr(trim($profileDescription), 0, 1500);
        $notes              = mb_substr(trim($notes), 0, 700);

        $swotText = $this->formatSwot($swot);

        // ---------- П О Л Н Ы Й   П Р О М П Т ----------
        $prompt = <<<PROMPT
You generate structured tasks for the Neurify.life platform.

You must output EXACT JSON with two fields:

{
  "comment": "...",
  "tasks": [
    {"title": "...", "idea": "..."},
    ...
  ]
}

Language for output: {$lang}
Strategy: {$strategy}

User background:
Artisan (self-positioning):
{$artisan}

Profile description:
{$profileDescription}

SWOT:
{$swotText}

User notes to AI:
{$notes}

OBJECTIVE:
1) Create a short comment (max 500 characters)
2) Create 6 tasks, each with:
   - "title": 3–7 words
   - "idea": 20–40 words (meaning, intention behind the task)

Rules:
- NO numbers in titles
- NO markdown
- NO plain text
- ONLY valid JSON
PROMPT;

        // ---------- DEEPSEEK REQUEST ----------
        $response = $this->http->request('POST', 'https://api.deepseek.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'    => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Return ONLY valid JSON.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'max_tokens'  => 1800,
                'temperature' => 0.7,
            ],
            'timeout' => 60,
        ]);

        $data  = $response->toArray(false);
        $json  = $data['choices'][0]['message']['content'] ?? '';

        // ЛОГИРУЕМ СЫРОЙ JSON
        file_put_contents(
            __DIR__ . '/../../var/log/deepseek_raw.log',
            '[' . date('Y-m-d H:i:s') . "] RAW:\n" . $json . "\n\n",
            FILE_APPEND
        );

        // Вырезаем JSON
        $json = $this->extractJson($json);

        // Авто-починка частых косяков
        $json = preg_replace('/],\s*]/', ']', $json);
        $json = preg_replace('/,\s*}/', '}', $json);
        $json = preg_replace('/,\s*\]/', ']', $json);

        // Основная попытка
        $parsed = json_decode($json, true);

        // Если JSON сломан — fallback-парсер
        if (json_last_error() !== JSON_ERROR_NONE) {
            $parsed = $this->attemptJsonRepair($json);
        }

        // Если fallback тоже не справился — жёсткая ошибка
        if (!$parsed || !isset($parsed['tasks']) || !isset($parsed['comment'])) {
            throw new \RuntimeException("Invalid JSON after repair: " . $json);
        }

        // Проверка структуры задач
        foreach ($parsed['tasks'] as $task) {
            if (!isset($task['title']) || !isset($task['idea'])) {
                throw new \RuntimeException("Task structure invalid: " . json_encode($task));
            }
        }

        return [
            'comment' => (string) $parsed['comment'],
            'tasks'   => $parsed['tasks']
        ];
    }

    /**
     * Форматирование SWOT перед отправкой в промпт
     */
    private function formatSwot(?Swot $swot): string
    {
        if (!$swot) {
            return "None.";
        }

        $parts = [];

        if ($swot->getStrengths()) {
            $parts[] = "Strengths: " . mb_substr($swot->getStrengths(), 0, 400);
        }
        if ($swot->getWeaknesses()) {
            $parts[] = "Weaknesses: " . mb_substr($swot->getWeaknesses(), 0, 400);
        }
        if ($swot->getOpportunities()) {
            $parts[] = "Opportunities: " . mb_substr($swot->getOpportunities(), 0, 400);
        }
        if ($swot->getThreats()) {
            $parts[] = "Threats: " . mb_substr($swot->getThreats(), 0, 400);
        }

        return $parts ? implode("\n", $parts) : "None.";
    }

    /**
     * Вырезаем JSON, даже если DeepSeek добавил мусор
     */
    private function extractJson(string $text): string
    {
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }
        return $text;
    }

    /**
     * Мощный fallback-парсер
     */
    private function attemptJsonRepair(string $text): ?array
    {
        // Удаляем невидимые символы
        $text = preg_replace('/[^\P{C}\n]+/u', '', $text);

        // Частые патчи
        $text = preg_replace('/],\s*]/', ']', $text);
        $text = preg_replace('/,\s*}/', '}', $text);
        $text = preg_replace('/,\s*\]/', ']', $text);

        // Попытка распарсить
        $parsed = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $parsed;
        }

        // Попытка вручную вытащить tasks[]
        if (preg_match('/"tasks"\s*:\s*\[(.*?)\]/s', $text, $m)) {
            $raw = '[' . $m[1] . ']';
            $raw = preg_replace('/},\s*]/', '}]', $raw);

            $tasks = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'comment' => '',
                    'tasks' => $tasks
                ];
            }
        }

        return null;
    }

    public function regenerateSingleTask(
        string $notes,
        string $lang,
        string $strategy,
        string $artisan,
        string $profileDescription,
        ?Swot $swot,
        array $existingTitles
    ): array {
        $artisan            = mb_substr(trim($artisan), 0, 300);
        $profileDescription = mb_substr(trim($profileDescription), 0, 1500);
        $notes              = mb_substr(trim($notes), 0, 700);

        $swotText = $this->formatSwot($swot);

        $titlesList = "- " . implode("\n- ", array_map('trim', $existingTitles));

        $prompt = <<<PROMPT
You generate ONE new task for the Neurify.life platform.

OUTPUT FORMAT (strict JSON):
{
  "title": "...",
  "idea": "..."
}

LANGUAGE: {$lang}
STRATEGY: {$strategy}

USER CONTEXT:
Artisan (self-positioning):
{$artisan}

Profile description:
{$profileDescription}

SWOT:
{$swotText}

User note for regeneration:
{$notes}

EXISTING TASK TITLES THAT MUST NOT BE REPEATED OR RESEMBLED:
{$titlesList}

OBJECTIVE:
Generate ONE *unique* task that:
- does NOT repeat or resemble any existing title
- introduces a NEW angle of improvement
- is consistent with user strategy
- is actionable and meaningful

TASK TITLE RULES:
- 3–7 words
- no numbers
- no quotes, no emojis
- must be clearly different from existing titles

TASK IDEA RULES:
- 20–40 words
- explain the true intention behind the task
- must be practical and actionable
- must NOT duplicate the meaning of existing tasks

RETURN ONLY STRICT JSON. No comments. No explanations.
PROMPT;

        for ($i = 0; $i < 3; $i++) {

            $response = $this->http->request('POST', 'https://api.deepseek.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'    => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Return ONLY valid JSON.'],
                        ['role' => 'user',   'content' => $prompt],
                    ],
                    'max_tokens'  => 600,
                    'temperature' => 0.7,
                ],
                'timeout' => 40,
            ]);

            $data  = $response->toArray(false);
            $raw   = $data['choices'][0]['message']['content'] ?? '';

            $json  = $this->extractJson($raw);

            // Попытка починки JSON
            $repaired = $this->attemptJsonRepair($json);

            // Если attemptJsonRepair() вернул массив → JSON уже разобран
            if (is_array($repaired)) {
                $parsed = $repaired;
            } else {
                // Иначе декодируем строку
                $parsed = json_decode((string)$repaired, true);
            }

            // Проверяем корректность данных
            if (!is_array($parsed) || !isset($parsed['title']) || !isset($parsed['idea'])) {
                continue; // retry
            }

            // Проверка
            if (!is_array($parsed) || !isset($parsed['title']) || !isset($parsed['idea'])) {
                continue;
            }

            if (!is_array($parsed) || !isset($parsed['title']) || !isset($parsed['idea'])) {
                continue; // retry
            }

            $newTitle = trim($parsed['title']);

            if (!in_array($newTitle, $existingTitles, true)) {
                return $parsed; // success
            }
        }

        throw new \RuntimeException("AI failed to generate unique task after 3 attempts");
    }

    public function generateSingleTask(string $lang, string $recommendation): array
    {
        $prompt = <<<PROMPT
You generate ONE task for the Neurify.life platform.

The task MUST be based on the following recommendation.
You MUST preserve the core meaning of the recommendation.

RECOMMENDATION:
{$recommendation}

OUTPUT FORMAT (STRICT JSON ONLY):
{
  "title": "...",
  "idea": "..."
}

LANGUAGE:
{$lang}

TASK TITLE RULES:
- 3–7 words
- no numbers
- no quotes
- no emojis
- action-oriented

TASK IDEA RULES:
- 20–40 words
- explain how to act on the recommendation
- practical and concrete

IMPORTANT:
If the task does NOT clearly reflect the recommendation,
the result is INVALID.

RETURN ONLY STRICT JSON.
NO explanations.
NO markdown.
PROMPT;

        for ($i = 0; $i < 3; $i++) {

            try {
                $response = $this->http->request(
                    'POST',
                    'https://api.deepseek.com/v1/chat/completions',
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $this->apiKey,
                            'Content-Type'  => 'application/json',
                        ],
                        'json' => [
                            'model' => 'deepseek-chat',
                            'messages' => [
                                ['role' => 'system', 'content' => 'Return ONLY valid JSON.'],
                                ['role' => 'user', 'content' => $prompt],
                            ],
                            'temperature' => 0.6,
                            'max_tokens'  => 300,
                        ],
                        'timeout' => 30,
                    ]
                );
            } catch (\Throwable $e) {
                continue;
            }


            $data = $response->toArray(false);
            $raw  = $data['choices'][0]['message']['content'] ?? '';

            // 1. Пытаемся вытащить JSON
            $json = $this->extractJson($raw);

            // 2. Чиним JSON (может вернуть array ИЛИ string)
            $repaired = $this->attemptJsonRepair($json);

            if (is_array($repaired)) {
                $parsed = $repaired;
            } else {
                $parsed = json_decode((string) $repaired, true);
            }

            // 3. Базовая проверка структуры
            if (
                !is_array($parsed) ||
                !isset($parsed['title'], $parsed['idea'])
            ) {
                continue;
            }

            $title = trim((string) $parsed['title']);
            $idea  = trim((string) $parsed['idea']);

            if ($title === '' || $idea === '') {
                continue;
            }

            // 4. Проверка 3–7 слов в title
            $words = preg_split('/\s+/u', $title);
            if ($words === false || count($words) < 3 || count($words) > 7) {
                continue;
            }

            return [
                'title' => $title,
                'idea'  => $idea,
            ];
        }

        throw new \RuntimeException('AI failed to generate valid task after 3 attempts');
    }
}