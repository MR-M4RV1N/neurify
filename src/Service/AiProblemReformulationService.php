<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiProblemReformulationService
{
    private $http;
    private $apiKey;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->apiKey = $deepseekApiKey;
    }

    /**
     * Генерирует множество альтернативных формулировок задачи
     * по методу Моргана Джонса.
     *
     * @param string $originalTitle
     * @param string $originalIdea
     * @param string $notes
     * @param string $lang
     * @param int $count
     * @return array  ["alternatives" => [...]]
     */
    public function generateProblemFormulations(
        string $originalTitle,
        string $originalIdea,
        string $notes,
        string $lang,
        int $count = 10
    ): array {
        $originalTitle = trim($originalTitle);
        $originalIdea  = trim($originalIdea);
        $notes         = mb_substr(trim($notes), 0, 700);

        // ---------- PROMPT ----------
        $prompt = <<<PROMPT
You must generate alternative problem formulations using the Morgan Jones
“New Problem Statement” technique for the Neurify.life platform.

LANGUAGE: {$lang}
OUTPUT: ONLY JSON. No markdown. No comments. No text outside JSON.

STRICT JSON FORMAT:
{
  "alternatives": [
    {
      "focus": "...",
      "formulation": "..."
    }
  ]
}

GOAL:
Produce EXACTLY {$count} reformulations of the original problem.
All output MUST be written in {$lang}.

EACH ALTERNATIVE MUST HAVE:
- "focus": a cognitive angle label (use ONLY labels from the list below exactly as written)
- "formulation": a 20–40 word problem statement or question reflecting that focus

ALLOWED FOCUS LABELS:
"Focus on benefit"
"Focus on problem"
"Focus on cause"
"Focus on user"
"Focus on goal"
"Focus on action"

RULES:
- ALWAYS return exactly {$count} elements inside "alternatives".
- Each formulation must reflect its chosen focus.
- Formulations must stay within the meaning domain of the original task.
- No solutions, only problem statements/questions.
- Output ONLY the JSON block.

INPUT:
Original title: "{$originalTitle}"
Original idea: "{$originalIdea}"
User notes: "{$notes}"
PROMPT;

        // ---------- SEND TO DEEPSEEK ----------
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
                'max_tokens'  => 1200,
                'temperature' => 0.7,
            ],
            'timeout' => 40,
        ]);

        $rawResponse = $response->getContent(false);
        if (!$rawResponse || trim($rawResponse) === '') {
            throw new \RuntimeException("DeepSeek returned empty response body");
        }
        $data = json_decode($rawResponse, true);
        if (!$data) {
            throw new \RuntimeException("DeepSeek returned non-JSON response: " . $rawResponse);
        }
        $raw = $data['choices'][0]['message']['content'] ?? '';

        // ---------- EXTRACT JSON ----------
        $json  = $this->extractJson($raw);
        $json  = $this->attemptJsonRepair($json);

        $parsed = json_decode($json, true);

        // Normalize cases where model returns array instead of object
        if (isset($parsed[0]) && is_array($parsed[0])) {
            $parsed = ['alternatives' => $parsed];
        }

        // If DeepSeek returned null or empty
        if (!isset($parsed['alternatives']) || !is_array($parsed['alternatives'])) {
            throw new \RuntimeException("DeepSeek returned invalid JSON (no alternatives)");
        }

        // Filter out invalid items
        $parsed['alternatives'] = array_values(array_filter($parsed['alternatives'], function ($item) {
            return isset($item['focus']) && isset($item['formulation']);
        }));

        if (count($parsed['alternatives']) === 0) {
            throw new \InvalidArgumentException("No valid alternatives provided.");
        }

        // DeepSeek sometimes returns a raw array instead of {"alternatives": [...]}
        if (is_array($parsed) && isset($parsed[0]) && is_array($parsed[0])) {
            $parsed = ['alternatives' => $parsed];
        }

        if (!isset($parsed['alternatives'])) {
            throw new \RuntimeException("DeepSeek returned invalid JSON structure");
        }

        return $parsed;
    }

    /**
     * Выбирает лучшую формулировку из списка
     * на основе критериев Моргана Джонса.
     *
     * @param array $alternatives
     * @param string $lang
     * @return array  ["selected" => [...]]
     */
    public function selectProblemFormulation(array $alternatives, string $lang): array
    {
        // 1. Очищаем входные данные, оставляем только валидные элементы
        $cleanList = [];
        foreach ($alternatives as $alt) {
            if (isset($alt['focus'], $alt['formulation'])) {
                $cleanList[] = [
                    'focus'       => trim($alt['focus']),
                    'formulation' => trim($alt['formulation']),
                ];
            }
        }

        if (empty($cleanList)) {
            throw new \InvalidArgumentException("No valid alternatives provided.");
        }

        $jsonInput = json_encode($cleanList, JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
You are applying the Morgan Jones method 
"New Problem Statement" (The Thinker's Toolkit).

TASK:
From the provided alternative problem formulations,
select ONE that is the most productive for further problem-solving.

EACH ITEM:
- "focus": cognitive angle label (same language as provided).
- "formulation": problem statement or question (same language as provided).

SELECTION CRITERIA:
- Clarity of the problem.
- Usefulness of the cognitive angle ("focus").
- How actionable the formulation is for next steps.
- How well it stays within the original meaning domain.

OUTPUT FORMAT:
Return ONLY JSON:

{
  "selected": {
    "focus": "...",
    "formulation": "...",
    "why": "Short explanation in {$lang}, 30–60 words."
  }
}

IMPORTANT:
- Output MUST be in {$lang}.
- Do NOT translate the input formulation or focus labels.
- Do NOT add commentary outside JSON.

ALTERNATIVES (JSON):
{$jsonInput}
PROMPT;

        $response = $this->http->request(
            'POST',
            'https://api.deepseek.com/v1/chat/completions',
            [
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
                    'temperature' => 0.5,
                ],
                'timeout' => 40,
            ]
        );

        $data  = $response->toArray(false);
        $raw   = $data['choices'][0]['message']['content'] ?? '';

        $json  = $this->extractJson($raw);
        $json  = $this->attemptJsonRepair($json);

        $parsed = json_decode($json, true);

        if (!is_array($parsed) || !isset($parsed['selected'])) {
            throw new \RuntimeException("DeepSeek failed to select problem formulation.");
        }

        // На всякий случай нормализуем
        if (!isset($parsed['selected']['focus'], $parsed['selected']['formulation'])) {
            throw new \RuntimeException("Selected formulation has invalid structure.");
        }

        return $parsed;
    }

    /**
     * Вспомогательный метод — извлечение JSON из ответа модели
     * (можешь заменить своим, если уже есть аналогичная функция)
     */
    private function extractJson(string $text): string
    {
        // Убираем markdown-обёртку
        $text = preg_replace('/```json/i', '', $text);
        $text = preg_replace('/```/i', '', $text);

        // 1) Ищем JSON-объект
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }

        // 2) Ищем JSON-массив
        if (preg_match('/\[(?:[^\[\]]|(?R))*\]/s', $text, $m)) {
            return $m[0];
        }

        // fallback — возвращаем как есть
        return trim($text);
    }

    /**
     * Вспомогательный метод — попытка восстановить повреждённый JSON
     * (можешь заменить своим existing attemptJsonRepair)
     */
    private function attemptJsonRepair(string $json): string
    {
        $json = trim($json);

        if ($json === '') {
            return '';
        }

        // Удаляем возможный BOM
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);

        // Удаляем мусор после последней закрывающей скобки
        $lastCurly = strrpos($json, '}');
        $lastSquare = strrpos($json, ']');
        $lastBracket = max($lastCurly !== false ? $lastCurly : -1, $lastSquare !== false ? $lastSquare : -1);
        if ($lastBracket !== -1) {
            $json = substr($json, 0, $lastBracket + 1);
        }

        // Частая ошибка: висячая запятая перед } или ]
        $json = preg_replace('/,\s*([}\]])/', '$1', $json);

        // Если JSON обрезан — пробуем дозакрыть
        $openCurly = substr_count($json, '{');
        $closeCurly = substr_count($json, '}');

        if ($openCurly > $closeCurly) {
            $json .= str_repeat('}', $openCurly - $closeCurly);
        }

        $openSquare = substr_count($json, '[');
        $closeSquare = substr_count($json, ']');

        if ($openSquare > $closeSquare) {
            $json .= str_repeat(']', $openSquare - $closeSquare);
        }

        // Иногда ключи могут быть без кавычек: repair them carefully
        if (json_decode($json, true) === null) {
            // Пробуем аккуратно окружить ключи кавычками: key: "value"
            $json = preg_replace('/(\w+)\s*:/', '"$1":', $json);
        }

        return trim($json);
    }

    public function makeProblemQuestionFromEvent(
        string $title,
        string $description,
        string $lang
    ): array {
        $title = trim($title);
        $description = trim($description);

        $prompt = <<<PROMPT
You are applying the Morgan Jones method
"New Problem Statement" (The Thinker's Toolkit).

TASK:
Rewrite the provided title and description into ONE clear problem statement or question
(20–40 words), suitable as the starting point for analytical reframing.

REQUIREMENTS:
- Must be written in the same language as the input.
- Must preserve the original meaning domain.
- Must NOT offer solutions or suggestions for action.
- Must describe the core problem or ask a question about it.
- 20–40 words only.

OUTPUT FORMAT (STRICT JSON):
{
  "problem": "..."
}

INPUT:
Title: "{$title}"
Description: "{$description}"
PROMPT;

        // ---- SEND TO DEEPSEEK ----
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
                'max_tokens'  => 300,
                'temperature' => 0.6,
            ],
            'timeout' => 40,
        ]);

        $data  = $response->toArray(false);
        $raw   = $data['choices'][0]['message']['content'] ?? '';

        // ---- EXTRACT JSON ----
        $json = $this->extractJson($raw);
        $json = $this->attemptJsonRepair($json);

        $parsed = json_decode($json, true);

        if (!is_array($parsed) || !isset($parsed['problem'])) {
            throw new \RuntimeException("DeepSeek did not return a valid problem statement JSON");
        }

        return $parsed;
    }
}