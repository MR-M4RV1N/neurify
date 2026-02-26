<?php

namespace App\Consulting\Model\PublicService;

use DateTimeImmutable;

class PublicServicePromptProvider
{
    private function getBasePrompt(): string
    {
        return <<<PROMPT
Ты — аналитик дневника, использующий конкретную методологию управления некоммерческими организациями и общественной деятельностью.
Всегда:
- опирайся только на факты текста (никаких догадок)
- если чего-то нет в тексте — прямо так и скажи
- выводи ТОЛЬКО валидный JSON строго по схеме из user-сообщения
- никаких пояснений, префиксов, markdown и "вот ваш JSON"
- ОЧЕНЬ ВАЖНО: Определи язык, на котором написан дневник, и напиши все текстовые значения в JSON (строки, массивы строк) ИМЕННО НА ЭТОМ ЯЗЫКЕ.
PROMPT;
    }

    public function getSystemPrompt(string $style, string $lang): string
    {
        $base = $this->getBasePrompt();

        if ($style === 'drucker') {
            return $this->getDruckerPrompt($base);
        }

        return $base;
    }

    public function getStyleShortLabel(string $style, string $lang): string
    {
        $labels = [
            'drucker' => [
                'ru' => 'Питер Друкер (Менеджмент НКО)',
                'lv' => 'Pīters Drukers (NVO menedžments)',
                'en' => 'Peter Drucker (Non-Profit Management)',
            ],
        ];

        return $labels[$style][$lang] ?? $labels[$style]['ru'] ?? $style;
    }

    public function getStyleDescription(string $style, string $lang): string
    {
        $descriptions = [
            'drucker' => [
                'ru' => 'Отец современного менеджмента. Оценит вашу общественную деятельность через призму миссии, ориентации на результат, работы с волонтерами и инноваций.',
                'lv' => 'Mūsdienu menedžmenta tēvs. Novērtēs jūsu sabiedrisko darbību caur misijas, uz rezultātu orientācijas, darbu ar brīvprātīgajiem un inovāciju prizmu.',
                'en' => 'The father of modern management. Will evaluate your public service through the prism of mission clarity, focus on results, volunteer coordination, and innovation.',
            ],
        ];

        return $descriptions[$style][$lang] ?? $descriptions[$style]['ru'] ?? '';
    }

    public function buildUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $style, string $lang): string
    {
        if ($style === 'drucker') {
            return $this->buildDruckerUserPrompt($weekStart, $weekText, $lang);
        }

        return '';
    }

    // --- PETER DRUCKER ---

    private function getDruckerPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — Питер Друкер, отец современного менеджмента. Твоя специфика сегодня: управление некоммерческими и общественными организациями (по мотивам твоей книги "Менеджмент в некоммерческой организации").
Твоя задача — проанализировать неделю лидера/волонтера/общественного деятеля.
Тебя интересует не прибыль, а "изменения в жизнях людей". Оцени его эффективность по 4 главным направлениям:
1. Ясность миссии: Помнит ли автор, ради чего это всё делается, или погряз в рутине?
2. Ориентация на результат: Достигнуты ли измеримые изменения или польза, либо это просто "суета ради суеты"?
3. Работа с командой/волонтерами: Как он взаимодействует с людьми, вдохновляет ли их (ведь неоплачиваемый труд держится на мотивации)?
4. Инновации и маркетинг: Ищет ли он новые способы сделать свою деятельность эффективнее и как привлекает ресурсы (внимание, деньги, время других людей)?

Тон: Профессиональный, глубокий, практичный. Как мудрый консультант для лидера НКО.
PROMPT;
    }

    private function buildDruckerUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        return <<<PROMPT
Проанализируй записи за неделю как Питер Друкер.
ВАЖНО: ОТВЕЧАЙ СТРОГО НА ТОМ ЖЕ ЯЗЫКЕ, НА КОТОРОМ НАПИСАНЫ САМИ ЗАПИСИ В ДНЕВНИКЕ (независимо от того, на каком языке написан этот промпт).

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "public_service_style": "drucker",
  "effectiveness_score": 0, // 0-10: Общая оценка эффективности управленца общественным проектом
  "drucker_metrics": { 
     "mission_clarity": 0,        // 0-100%: Насколько ясно прослеживается фокус на глобальной "Миссии"
     "results_focus": 0,          // 0-100%: Ориентация на реальные результаты/пользу для целевой аудитории
     "team_and_volunteers": 0,    // 0-100%: Качество работы с людьми, командой, волонтерами
     "innovation_and_marketing": 0 // 0-100%: Поиск новых решений и привлечение ресурсов/внимания
  },
  "leadership_insight": "Текст (1 предложение): Главное наблюдение о том, как автор проявляет лидерство в своей инициативе.",
  "resource_allocation": "Текст (1 предложение): Насколько компетентно распределяются ресурсы (время, фокус, люди) - инвестирует ли он в сильные стороны?",
  "recommendations": [
     // 3 практических совета "от Друкера" лидеру некоммерческого проекта
  ],
  "summary": "Краткий итог анализа деятельности за неделю в стиле Питера Друкера (1 абзац)."
}
PROMPT;
    }
}
