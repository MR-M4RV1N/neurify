<?php

namespace App\Service\ThinkingTools;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiReverseAssumptionsService
{
    private $http;
    private $apiKey;

    public function __construct(HttpClientInterface $http, string $deepseekApiKey)
    {
        $this->http = $http;
        $this->apiKey = $deepseekApiKey;
    }

    /**
     * Generate reversed assumptions based on
     * Michalko's “Reverse the assumptions” method
     * (“Рисовый штурм”, гл. 5, стр. 63–67).
     *
     * @return array
     *   [
     *     "assumptions" => [
     *        [
     *          "original"  => "...",
     *          "reversed"  => "...",
     *          "insight"   => "..."
     *        ]
     *     ]
     *   ]
     */
    public function generateReverseAssumptions(
        string $eventTitle,
        string $eventDescription,
        string $lang,
        int $count = 6
    ): array {

        $originalTitle = trim($eventTitle);
        $originalIdea  = trim($eventDescription);
        $count         = max(3, min($count, 12));

        // === PROMPT ===
        $prompt = <<<PROMPT
You must apply Michael Michalko’s creative method
“Reverse the Assumptions” as described in the book 
“Рисовый штурм”, chapter 5 (pages 63–67).

TASK:
Identify the hidden assumptions behind the problem.
Then reverse each assumption (“what if the opposite is true?”) 
and generate insights based on these reversals.

LANGUAGE FOR OUTPUT: {$lang}

STRICT JSON OUTPUT ONLY:
{
  "assumptions": [
    {
      "original": "...",
      "reversed": "...",
      "insight": "..."
    }
  ]
}

OUTPUT REQUIREMENTS:

- Produce EXACTLY {$count} reversed assumptions.
- Each assumption object MUST contain:
    - "original": a short hidden assumption taken from the way the problem is usually understood.
    - "reversed": a logically opposite or inverted version of the assumption.
    - "insight": 20–45 words explaining what new ideas, directions, or opportunities emerge from the inversion.

- All text MUST be written in {$lang}.
- Do NOT propose solutions. Focus only on assumptions, reversals, and insights.
- Output ONLY JSON with no markdown, no comments.

INPUT:
Original problem title: "{$eventTitle}"
Original idea: "{$eventDescription}"
PROMPT;

        // === SEND TO DEEPSEEK WITH RETRY ===
        $raw = null;

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
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

                $content = $response->getContent(false);

                if ($content && trim($content) !== '') {
                    $raw = $content;
                    break;
                }

            } catch (\Throwable $e) {
                // allow retry
            }

            usleep(150000); // 150ms pause
        }

        if (!$raw || trim($raw) === '') {
            throw new \RuntimeException("ReverseAssumptions: empty response after 3 attempts");
        }

        // === PARSE RAW JSON STRUCTURE ===
        $data = json_decode($raw, true);

        // If chat-style JSON was returned, extract message.content
        if (isset($data['choices'][0]['message']['content'])) {
            $raw = $data['choices'][0]['message']['content'];
        }

        $json = $this->extractJson($raw);
        $json = $this->attemptJsonRepair($json);

        $parsed = json_decode($json, true);

        // DeepSeek sometimes returns array instead of {"assumptions": [...]}
        if (is_array($parsed) && isset($parsed[0]) && is_array($parsed[0])) {
            $parsed = ['assumptions' => $parsed];
        }

        if (!isset($parsed['assumptions']) || !is_array($parsed['assumptions'])) {
            throw new \RuntimeException("ReverseAssumptions: invalid JSON structure");
        }

        // Filter invalid items
        $parsed['assumptions'] = array_values(array_filter($parsed['assumptions'], function ($item) {
            return isset($item['original'], $item['reversed'], $item['insight']);
        }));

        if (count($parsed['assumptions']) === 0) {
            throw new \RuntimeException("ReverseAssumptions: no valid assumptions generated");
        }

        return $parsed;
    }


    // === JSON HELPERS ===

    private function extractJson(string $text): string
    {
        $text = preg_replace('/```json/i', '', $text);
        $text = preg_replace('/```/i', '', $text);

        // Try to extract object
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }

        // Try to extract array
        if (preg_match('/\[(?:[^\[\]]|(?R))*\]/s', $text, $m)) {
            return $m[0];
        }

        return trim($text);
    }

    private function attemptJsonRepair(string $json): string
    {
        $json = trim($json);
        if ($json === '') {
            return $json;
        }

        // Remove BOM
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);

        // Trim garbage after final bracket
        $lastCurly  = strrpos($json, '}');
        $lastSquare = strrpos($json, ']');
        $lastPos    = max($lastCurly !== false ? $lastCurly : -1, $lastSquare !== false ? $lastSquare : -1);

        if ($lastPos !== -1) {
            $json = substr($json, 0, $lastPos + 1);
        }

        // Remove trailing commas
        $json = preg_replace('/,\s*([}\]])/', '$1', $json);

        // Balance brackets if needed
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