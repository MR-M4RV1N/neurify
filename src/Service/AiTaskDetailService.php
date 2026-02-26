<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiTaskDetailService
{
    private $http;
    private $apiKey;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->apiKey = $deepseekApiKey;
    }

    public function generateTaskDetails(string $taskTitle, string $idea, string $lang): array
    {
        $prompt = <<<PROMPT
Generate description and instruction for this task.

Task title:
"{$taskTitle}"

Task core idea / intention:
{$idea}

Return STRICT JSON:
{
  "description": "...",
  "instruction": "1. ...\n2. ...\n3. ..."
}

RULES FOR DESCRIPTION (≤160 chars):
- Generate ONLY the final outcome the user gains after completing the task.
- It must describe a new ability, clarity, resource, result, or improvement
that remains after the task is done.
- Do NOT describe actions, process, steps, or what to do.
- Do NOT use verbs about work (improve, create, analyze, prepare, organize).
- Do NOT include motivational or explanatory text.

Keep the description static, concrete, observable, and realistic.
Length ≤ 160 characters.

RULES FOR INSTRUCTION (≤300 chars):
- Provide 1–3 single, non-repetitive steps ("1. ... 2. ... 3. ...").
- NO routine (no “regularly”, “daily”, “continue”).
- NO vague verbs (“try”, “consider”, “explore”).
- Each step must be a single concrete one-time action.
- MUST follow the IDEA exactly.

GENERAL:
- Language: {$lang}
- Output ONLY JSON, no text, no markdown.
PROMPT;

        $response = $this->http->request('POST', 'https://api.deepseek.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Return ONLY JSON. No markdown.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ],
        ]);

        $data = $response->toArray(false);
        $json = $data['choices'][0]['message']['content'] ?? '';

        // --- Вырезаем JSON из markdown ---
        $json = $this->extractJson($json);

        // --- Простейшие правки DeepSeek ---
        $json = preg_replace('/,\s*}/', '}', $json);
        $json = preg_replace('/,\s*]/', ']', $json);

        $decoded = json_decode($json, true);
        if (!$decoded || !isset($decoded['description'])) {
            throw new \RuntimeException("Invalid JSON: " . $json);
        }

        return $decoded;
    }

    /**
     * Вытаскивает JSON-объект даже если DeepSeek обернул его в ```json ... ```
     */
    private function extractJson(string $text): string
    {
        // Убираем markdown-обёртку
        $text = preg_replace('/```json/i', '', $text);
        $text = preg_replace('/```/i', '', $text);

        // Ищем JSON через рекурсивную регулярку
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }

        return $text;
    }
}