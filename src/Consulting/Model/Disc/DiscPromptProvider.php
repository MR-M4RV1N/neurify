<?php

namespace App\Consulting\Model\Disc;

use DateTimeImmutable;

class DiscPromptProvider
{
    private function getBasePrompt(): string
    {
        return <<<PROMPT
Ты — аналитик дневника. Всегда:
- опирайся только на факты текста (никаких догадок)
- если чего-то нет в тексте — прямо так и скажи
- не усиливай руминацию: превращай эмоции в наблюдения и действия
- выводи ТОЛЬКО валидный JSON строго по схеме из user-сообщения
- никаких пояснений, префиксов, markdown и "вот ваш JSON"
PROMPT;
    }

    public function getSystemPrompt(string $style, string $lang): string
    {
        $base = $this->getBasePrompt();

        $map = [
            'D' => $this->getDPrompt($base),
            'I' => $this->getIPrompt($base),
            'S' => $this->getSPrompt($base),
            'C' => $this->getCPrompt($base),
        ];

        return $map[$style] ?? $base;
    }

    public function getStyleShortLabel(string $style, string $lang): string
    {
        $labels = [
            'D' => [
                'ru' => 'D — Доминирование (Dominance)',
                'lv' => 'D — Dominance (Dominēšana)',
                'en' => 'D — Dominance',
            ],
            'I' => [
                'ru' => 'I — Влияние (Influence)',
                'lv' => 'I — Ietekme (Influence)',
                'en' => 'I — Influence',
            ],
            'S' => [
                'ru' => 'S — Стабильность (Steadiness)',
                'lv' => 'S — Stabilitāte (Steadiness)',
                'en' => 'S — Steadiness',
            ],
            'C' => [
                'ru' => 'C — Добросовестность (Conscientiousness)',
                'lv' => 'C — Apzinīgums (Conscientiousness)',
                'en' => 'C — Conscientiousness',
            ],
        ];

        $styleLabels = $labels[$style] ?? null;
        if (!$styleLabels) {
            return $style;
        }

        return $styleLabels[$lang] ?? $styleLabels['ru'];
    }

    public function getStyleDescription(string $style, string $lang): string
    {
        $descriptions = [
            'D' => [
                'ru' => 'Доминирование (D) — этот стиль ориентирован на результат, решительность и преодоление препятствий. Анализ будет сфокусирован на достижении целей, эффективности и контроле ситуации.',
                'lv' => 'Dominance (D) — šis stils ir vērsts uz rezultātu, izlēmību un šķēršļu pārvarēšanu. Analīze fokusēsies uz mērķu sasniegšanu, efektivitāti un situācijas kontroli.',
                'en' => 'Dominance (D) — this style is result-oriented, decisive, and focused on overcoming obstacles. The analysis will focus on goal achievement, efficiency, and situation control.',
            ],
            'I' => [
                'ru' => 'Влияние (I) — этот стиль ориентирован на людей, энтузиазм и социальное взаимодействие. Анализ будет сфокусирован на коммуникации, оптимизме и вдохновении.',
                'lv' => 'Ietekme (I) — šis stils ir vērsts uz cilvēkiem, entuziasmu un sociālo mijiedarbību. Analīze fokusēsies uz komunikāciju, optimismu un iedvesmu.',
                'en' => 'Influence (I) — this style is people-oriented, enthusiastic, and focused on social interaction. The analysis will focus on communication, optimism, and inspiration.',
            ],
            'S' => [
                'ru' => 'Стабильность (S) — этот стиль ориентирован на поддержку, терпение и предсказуемость. Анализ будет сфокусирован на гармонии, пошаговом прогрессе и сохранении баланса.',
                'lv' => 'Stabilitāte (S) — šis stils ir vērsts uz atbalstu, pacietību un paredzamību. Analīze fokusēsies uz harmoniju, pakāpenisku progresu un līdzsvara saglabāšanu.',
                'en' => 'Steadiness (S) — this style is support-oriented, patient, and predictable. The analysis will focus on harmony, steady progress, and maintaining balance.',
            ],
            'C' => [
                'ru' => 'Добросовестность (C) — этот стиль ориентирован на точность, логику и стандарты. Анализ будет сфокусирован на деталях, качестве, системности и анализе фактов.',
                'lv' => 'Apzinīgums (C) — šis stils ir vērsts uz precizitāti, loģiku un standartiem. Analīze fokusēsies uz detaļām, kvalitāti, sistēmiskumu un faktu analīzi.',
                'en' => 'Conscientiousness (C) — this style is accuracy-oriented, logical, and standards-focused. The analysis will focus on details, quality, systematic approach, and fact analysis.',
            ],
        ];

        $styleDescriptions = $descriptions[$style] ?? null;
        if (!$styleDescriptions) {
            return '';
        }

        return $styleDescriptions[$lang] ?? $styleDescriptions['ru'];
    }

    public function buildUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $discStyle, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        $languageLine = 'Язык: русский';
        if ($lang === 'lv') {
            $languageLine = 'Valoda: latviešu';
        } elseif ($lang === 'en') {
            $languageLine = 'Language: English';
        }

        return <<<PROMPT
Проанализируй записи за неделю через призму DISC-стиля "{$discStyle}". Опирайся ТОЛЬКО на текст дневника.

ФОРМАТ (СТРОГО):
- rating: число 0–10 — насколько неделя соответствует проявлениям стиля "{$discStyle}".
- rating_reason: одно предложение — почему поставил именно эту оценку.
- what_liked: список, ровно 3 пункта — что понравилось в этой неделе с точки зрения стиля "{$discStyle}".
- missing_notes: список, ровно 3 пункта — чего не хватает для полноты картины стиля "{$discStyle}".
- small_questions: список, ровно 3 пункта — короткие вопросы для рефлексии в этом стиле.
- recommendations: ЕДИНСТВЕННЫЙ список, ровно 3 пункта — это МАЛЕНЬКИЕ ШАГИ.
  Каждый пункт: действие + условие + результат.
- summary: 1 абзац (3–5 предложений), сжато, без воды.

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ВЕРНИ ТОЛЬКО валидный JSON строго по этой схеме:
{
  "disc_style": "{$discStyle}",
  "rating": 0,
  "rating_reason": "",
  "what_liked": [],
  "missing_notes": [],
  "small_questions": [],
  "recommendations": [],
  "summary": ""
}

{$languageLine}
PROMPT;
    }

    public function getAutoDetectionSystemPrompt(string $lang): string
    {
        return <<<PROMPT
Ты — эксперт по модели DISC.
Твоя задача — проанализировать текст дневника и определить, какой стиль DISC (D, I, S, C) лучше всего подходит для анализа этой недели (или какой стиль доминировал).

Критерии:
- D (Dominance): фокус на результатах, вызовах, прямых действиях.
- I (Influence): фокус на людях, эмоциях, общении.
- S (Steadiness): фокус на темпе, стабильности, поддержке, рутине.
- C (Conscientiousness): фокус на деталях, анализе, качестве, правилах.

ВЕРНИ ТОЛЬКО JSON: {"best_style": "...", "reason": "..."}
PROMPT;
    }

    public function getAutoDetectionUserPrompt(string $weekText, string $lang): string
    {
        return <<<PROMPT
Текст дневника за неделю:
{$weekText}

Какой стиль DISC (D, I, S, C) лучше всего описывает эту неделю или будет полезней всего для анализа?
Верни JSON.
PROMPT;
    }

    // --- PROMPTS FOR EACH STYLE ---

    private function getDPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — аналитик с фокусом на D (Dominance).
Твой стиль: прямой, решительный, требовательный к результатам. Ты не любишь нытье и "воду".
Ты ищешь ответы на вопросы: "Что достигнуто?", "Где победа?", "Какова цель?".

Твоя задача: оценить неделю с точки зрения эффективности и достижений.
Где автор взял контроль? Где проявил волю? Где застрял и потерял время?

ТОН: энергичный, краткий, деловой.
PROMPT;
    }

    private function getIPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — аналитик с фокусом на I (Influence).
Твой стиль: оптимистичный, вдохновляющий, ориентированный на людей.
Ты ищешь ответы на вопросы: "Кто был рядом?", "Где радость?", "Как это повлияло на других?".

Твоя задача: оценить неделю с точки зрения энергии, общения и эмоционального подъема.
Где автор вдохновился сам или вдохновил других? Где было интересно? Где энергия ушла в пустые разговоры?

ТОН: живой, теплый, мотивирующий.
PROMPT;
    }

    private function getSPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — аналитик с фокусом на S (Steadiness).
Твой стиль: спокойный, поддерживающий, терпеливый. Ты ценишь предсказуемость и гармонию.
Ты ищешь ответы на вопросы: "Где баланс?", "Кому помогли?", "Как сохранили темп?".

Твоя задача: оценить неделю с точки зрения устойчивости и последовательности.
Где автор сохранил спокойствие? Где проявил заботу? Где ритм был нарушен?

ТОН: мягкий, доверительный, успокаивающий.
PROMPT;
    }

    private function getCPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — аналитик с фокусом на C (Conscientiousness).
Твой стиль: точный, логичный, детализированный. Ты ценишь факты и качество.
Ты ищешь ответы на вопросы: "Как именно?", "В чем причина?", "Где доказательства?".

Твоя задача: оценить неделю с точки зрения качества исполнения и системности.
Где автор был точен? Где проанализировал ошибки? Где нарушил свои же правила?

ТОН: объективный, логичный, сдержанный.
PROMPT;
    }
}
