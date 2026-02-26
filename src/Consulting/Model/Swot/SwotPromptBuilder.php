<?php

namespace App\Consulting\Model\Swot;

use App\Entity\User;

class SwotPromptBuilder
{
    /**
     * Собирает prompt для SWOT-консультирования
     *
     * @param array{
     *   profile: string,
     *   swot: array|null,
     *   eventsBrief: string
     * } $context
     */
    public function build(User $user, string $lang, array $context): string
    {
        $profile = $this->normalize($context['profile'] ?? '', 1200);
        $events  = $context['eventsBrief'] ?? 'No recent events.';

        $swotText = $this->formatSwot($context['swot'] ?? null);

        return <<<PROMPT
You are a personal development consultant using the SWOT framework.

Always respond in the language specified below.
If the language code is "ru", respond in Russian.
If "en", respond in English.
If "lv", respond in Latvian.

[LANG]
{$lang}

SWOT FRAMEWORK RULES:
- Strengths: internal advantages
- Weaknesses: internal limitations
- Opportunities: external chances for growth
- Threats: external risks and obstacles

USER PROFILE:
{$profile}

USER SWOT:
{$swotText}

RECENT EVENTS:
{$events}

YOUR TASK:
1) Analyze the user's situation strictly through the SWOT framework.
2) Identify the most important strategic focus for the next period.
3) Generate practical recommendations based on:
   - using strengths
   - compensating weaknesses
   - leveraging opportunities
   - reducing threats

OUTPUT FORMAT — STRICT JSON ONLY:

{
  "focus": "one short sentence describing the main strategic focus",
  "analysis": {
    "strengths": ["...", "..."],
    "weaknesses": ["...", "..."],
    "opportunities": ["...", "..."],
    "threats": ["...", "..."]
  },
  "recommendations": [
    "clear, practical recommendation",
    "clear, practical recommendation",
    "clear, practical recommendation"
  ],
  "summary": "2–3 sentences explaining the overall strategic direction"
}

REQUIREMENTS:
- All arrays must contain at least 2 items.
- "recommendations" must contain at least 3 items.
- No empty strings.
- No explanations outside JSON.
- No markdown.
- No line breaks inside JSON string values.
- Return ONLY valid JSON.
PROMPT;
    }

    // ======================
    // Helpers
    // ======================

    private function formatSwot(?array $swot): string
    {
        if (!$swot) {
            return 'No SWOT data provided.';
        }

        return
            "Strengths:\n"     . $this->normalize($swot['strengths'] ?? '', 400) . "\n\n" .
            "Weaknesses:\n"    . $this->normalize($swot['weaknesses'] ?? '', 400) . "\n\n" .
            "Opportunities:\n". $this->normalize($swot['opportunities'] ?? '', 400) . "\n\n" .
            "Threats:\n"       . $this->normalize($swot['threats'] ?? '', 400);
    }

    private function normalize(string $text, int $limit): string
    {
        $t = strip_tags($text);
        $t = preg_replace('/\s+/u', ' ', $t);
        $t = trim($t);

        if (mb_strlen($t, 'UTF-8') > $limit) {
            $t = mb_substr($t, 0, $limit, 'UTF-8') . '…';
        }

        return $t;
    }
}