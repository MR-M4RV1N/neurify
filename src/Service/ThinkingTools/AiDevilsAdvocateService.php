<?php

namespace App\Service\ThinkingTools;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiDevilsAdvocateService
{
    private $http;
    private $apiKey;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->apiKey = $deepseekApiKey;
    }

    /**
     * Генерирует набор возражений в стиле "Адвокат дьявола"
     * по методике Моргана Джонса (гл. 12).
     *
     * @param string $eventTitle  Краткая формулировка задачи/проблемы
     * @param string $eventDescription  Описание задачи/проблемы
     * @param string $lang          Язык пользователя (например: "ru", "lv", "en")
     * @param int    $count         Сколько возражений сгенерировать
     *
     * @return array ["critiques" => [ [type, objection, explanation], ... ]]
     */
    public function generateDevilsAdvocateCritique(
        string $eventTitle,
        string $eventDescription,
        string $lang,
        int $count = 6
    ): array {
        $eventTitle = trim($eventTitle);
        $eventDescription = trim($eventDescription);
        $count        = max(3, min($count, 12));

        $prompt = <<<PROMPT
You are acting as a professional "Devil's Advocate" 
using Morgan Jones's method "Devil's Advocate" 
(from his decision-making toolkit).

TASK:
Generate EXACTLY {$count} critical challenges to the following problem statement.
Your goal is to stress-test the problem by attacking it from different angles.

LANGUAGE FOR OUTPUT: {$lang}

STRICT JSON OUTPUT ONLY:
{
  "critiques": [
    {
      "type": "assumption | logic | consequence | risk | alternative",
      "objection": "...",
      "explanation": "..."
    }
  ]
}

DEFINITIONS:
- "assumption": you attack hidden assumptions behind the problem.
- "logic": you attack the reasoning, causal links or internal consistency.
- "consequence": you attack possible outcomes or side effects if we follow this problem as stated.
- "risk": you highlight dangers, vulnerabilities, or what could go wrong.
- "alternative": you propose a completely different angle: is this even the right problem?

REQUIREMENTS FOR EACH ITEM:
- "type":
    - one of: "assumption", "logic", "consequence", "risk", "alternative"
- "objection":
    - 1–2 sentences
    - clear, sharp critical question or statement
    - MUST be written in {$lang}
- "explanation":
    - 20–50 words
    - deeper elaboration of why this is a serious concern
    - MUST be written in {$lang}

RULES:
- Generate EXACTLY {$count} critiques inside "critiques".
- Use different types and angles; do not repeat the same point.
- Do NOT propose solutions; only criticism and questioning.
- Output ONLY valid JSON, no markdown, no comments, no text before or after JSON.

INPUT:
Event title: "{$eventTitle}"
Event description: "{$eventDescription}"

PROMPT;

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
                'max_tokens'  => 1000,
                'temperature' => 0.7,
            ],
            'timeout' => 40,
        ]);

        $content = $response->getContent(false);
        if (!$content || trim($content) === '') {
            throw new \RuntimeException("DeepSeek: empty response body");
        }
        $data = json_decode($content, true);

        if (!is_array($data) || !isset($data['choices'][0]['message']['content'])) {
            throw new \RuntimeException("DeepSeek: response missing expected structure");
        }
        $raw   = $data['choices'][0]['message']['content'] ?? '';

        $json  = $this->extractJson($raw);
        $json  = $this->attemptJsonRepair($json);

        $parsed = json_decode($json, true);

        // DeepSeek иногда может вернуть просто массив, нормализуем
        if (is_array($parsed) && isset($parsed[0]) && is_array($parsed[0])) {
            $parsed = ['critiques' => $parsed];
        }

        if (!is_array($parsed) || !isset($parsed['critiques']) || !is_array($parsed['critiques'])) {
            throw new \RuntimeException("Devil's Advocate: invalid JSON structure from AI");
        }

        // Фильтруем только валидные элементы
        $parsed['critiques'] = array_values(array_filter($parsed['critiques'], function ($item) {
            return isset($item['type'], $item['objection'], $item['explanation']);
        }));

        if (count($parsed['critiques']) === 0) {
            throw new \RuntimeException("Devil's Advocate: no valid critiques generated");
        }

        return $parsed;
    }

    /**
     * Вытаскивает JSON-объект/массив даже если модель обернула его в текст/```json
     */
    private function extractJson(string $text): string
    {
        // Убираем markdown-обёртку
        $text = preg_replace('/```json/i', '', $text);
        $text = preg_replace('/```/i', '', $text);

        // 1) Пытаемся найти объект
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }

        // 2) Пытаемся найти массив
        if (preg_match('/\[(?:[^\[\]]|(?R))*\]/s', $text, $m)) {
            return $m[0];
        }

        return trim($text);
    }

    /**
     * Лёгкая попытка починить битый JSON (висячие запятые, мусор в конце и т.п.)
     */
    private function attemptJsonRepair(string $json): string
    {
        $json = trim($json);
        if ($json === '') {
            return $json;
        }

        // Удаляем возможный BOM
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);

        // Удаляем мусор после последней закрывающей скобки
        $lastCurly  = strrpos($json, '}');
        $lastSquare = strrpos($json, ']');
        $lastBracket = max($lastCurly !== false ? $lastCurly : -1, $lastSquare !== false ? $lastSquare : -1);
        if ($lastBracket !== -1) {
            $json = substr($json, 0, $lastBracket + 1);
        }

        // Висячие запятые перед } или ]
        $json = preg_replace('/,\s*([}\]])/', '$1', $json);

        // Баланс скобок
        $openCurly  = substr_count($json, '{');
        $closeCurly = substr_count($json, '}');
        if ($openCurly > $closeCurly) {
            $json .= str_repeat('}', $openCurly - $closeCurly);
        }

        $openSquare  = substr_count($json, '[');
        $closeSquare = substr_count($json, ']');
        if ($openSquare > $closeSquare) {
            $json .= str_repeat(']', $openSquare - $closeSquare);
        }

        return trim($json);
    }
}