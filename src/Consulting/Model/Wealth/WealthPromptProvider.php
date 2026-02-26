<?php

namespace App\Consulting\Model\Wealth;

use DateTimeImmutable;

class WealthPromptProvider
{
    private function getBasePrompt(): string
    {
        return <<<PROMPT
Ты — аналитик дневника, использующий конкретную методологию мышления.
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

        if ($style === 'hill') {
            return $this->getHillPrompt($base);
        }

        if ($style === 'clason') {
            return $this->getClasonPrompt($base);
        }

        // Заглушка для остальных (например, если позже добавим Кийосаки)
        return $base;
    }

    public function getStyleShortLabel(string $style, string $lang): string
    {
        $labels = [
            'hill' => [
                'ru' => 'Наполеон Хилл (Думай и богатей)',
                'lv' => 'Napoleons Hills (Domā un kļūsti bagāts)',
                'en' => 'Napoleon Hill (Think and Grow Rich)',
            ],
            'clason' => [
                'ru' => 'Дж. Клейсон (Самый богатый человек в Вавилоне)',
                'lv' => 'Dž. Kleisons (Pasaules bagātākais cilvēks Bābelē)',
                'en' => 'G. Clason (The Richest Man in Babylon)',
            ],
        ];

        return $labels[$style][$lang] ?? $labels[$style]['ru'] ?? $style;
    }

    public function getStyleDescription(string $style, string $lang): string
    {
        $descriptions = [
            'hill' => [
                'ru' => 'Наполеон Хилл — классик литературы успеха. Анализ сфокусирован на целях, настойчивости и принципах достижения богатства.',
                'lv' => 'Napoleons Hills ir panākumu literatūras klasiķis. Analīze fokusēsies uz mērķiem, neatlaidību un bagātības sasniegšanas principiem.',
                'en' => 'Napoleon Hill is a classic of success literature. The analysis will focus on goals, persistence, and principles of achieving wealth.',
            ],
            'clason' => [
                'ru' => 'Джордж Самюэль Клейсон расскажет о фундаментальных законах денег. Анализ через призму "7 лекарств от тощего кошелька" и "5 законов золота".',
                'lv' => 'Džordžs Semjuels Kleisons pastāstīs par fundamentāliem naudas likumiem. Analīze caur "7 zālēm pret tukšu maku" un "5 zelta likumiem".',
                'en' => 'George Samuel Clason will share fundamental laws of money. Analysis through the prism of the "7 cures for a lean purse" and the "5 laws of gold".',
            ],
        ];

        return $descriptions[$style][$lang] ?? $descriptions[$style]['ru'] ?? '';
    }

    public function buildUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $style, string $lang): string
    {
        if ($style === 'hill') {
            return $this->buildHillUserPrompt($weekStart, $weekText, $lang);
        }

        if ($style === 'clason') {
            return $this->buildClasonUserPrompt($weekStart, $weekText, $lang);
        }

        return '';
    }

    // --- NAPOLEON HILL ---

    private function getHillPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — Наполеон Хилл, эксперт по философии успеха и достижения богатства.
Твоя задача — проанализировать неделю автора через призму твоих 13 принципов успеха:
1. ОПРЕДЕЛЕННОСТЬ ЦЕЛИ: Были ли действия автора направлены на достижение Главной Цели, или он просто дрейфовал?
2. МАСТЕР-ГРУППА (Brain Trust): Использовал ли автор силу окружения и гармоничного сотрудничества?
3. НАСТОЙЧИВОСТЬ: Как автор преодолевал временные неудачи? Сдавался или шел вперед?
4. САМОВНУШЕНИЕ: О чем думал автор? Управлял ли он своими мыслями, или позволял страхам и сомнениям (Дьявол) брать верх?
5. ВЕРА и ВООБРАЖЕНИЕ: Видит ли автор четкую картину своего успеха?

Тон: Вдохновляющий, но строгий. Требующий дисциплины мысли. Используй термины "Главная Определенная Цель", "Дрейф", "Временное поражение".
PROMPT;
    }

    private function buildHillUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        return <<<PROMPT
Проанализируй записи за неделю как Наполеон Хилл.
ВАЖНО: ОТВЕЧАЙ СТРОГО НА ТОМ ЖЕ ЯЗЫКЕ, НА КОТОРОМ НАПИСАНЫ САМИ ЗАПИСИ В ДНЕВНИКЕ (независимо от того, на каком языке написан этот промпт).

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "wealth_style": "hill",
  "determination_score": 0, // 0-10: Насколько сильно "Жгучее Желание" и настойчивость автора на этой неделе
  "goal_alignment": {
     "aligned": 0, // % Времени/действий, явно ведущих к Главной Цели
     "drift": 0    // % Времени/действий, потраченных впустую (Дрейф, прокрастинация)
     // Сумма должна быть 100
  },
  "principles": {
     "faith": 0,             // 0-100%: Уверенность в себе и позитивное ожидание
     "auto_suggestion": 0,   // 0-100%: Контроль мыслей и позитивный внутренний диалог
     "specialized_knowledge": 0, // 0-100%: Приобретение или использование конкретных знаний для цели
     "imagination": 0        // 0-100%: Использование синтетического или творческого воображения
  },
  "mastermind_status": "Текст (1-2 предложения): Использовал ли автор силу Мастер-группы (окружения) или действовал в одиночку?",
  "recommendations": [
     // 3 совета в стиле Хилла (записать цель, найти союзников, не сдаваться)
  ],
  "summary": "Краткий и мотивирующий итог недели в стиле Наполеона Хилла (1 абзац)."
}
PROMPT;
    }

    // --- GEORGE S. CLASON ---

    private function getClasonPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — Аркад, самый богатый человек в древнем Вавилоне (в стиле книги Джорджа Клейсона).
Твоя задача — проанализировать финансовые и жизненные привычки автора за неделю, используя உன்у вековую мудрость.
Оцени его действия через:
1. "7 лекарств от тощего кошелька" (Откладывает ли он 10%? Контролирует траты? Умножает богатство? и т.д.)
2. Отношение к труду: Воспринимает ли он работу как лучшего друга, который приносит богатство, или как бремя?
3. Тягу к знаниям: Заботится ли он об увеличении своей способности зарабатывать (учится ли новому)?

Тон: Мудрый, архаичный, спокойный, как у опытного наставника, делящегося секретами на улицах Вавилона. Используй метафоры: "монеты", "золото", "кошелек", "посевы", "рабство долгов".
PROMPT;
    }

    private function buildClasonUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');

        return <<<PROMPT
Проанализируй записи за неделю как Аркад из "Самого богатого человека в Вавилоне".
ВАЖНО: ОТВЕЧАЙ СТРОГО НА ТОМ ЖЕ ЯЗЫКЕ, НА КОТОРОМ НАПИСАНЫ САМИ ЗАПИСИ В ДНЕВНИКЕ (независимо от того, на каком языке написан этот промпт).

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "wealth_style": "clason",
  "wealth_building_score": 0, // 0-10: Насколько успешно автор следует законам богатства на этой неделе
  "cures_assessment": { // 0-100% (насколько автор вкладывался в эти аспекты)
     "pay_yourself_first": 0, // Начните пополнять кошелек (сбережения)
     "control_expenses": 0,   // Контролируй расходы
     "multiply_gold": 0,      // Приумножай богатство (инвестиции/бизнес)
     "increase_ability": 0    // Совершенствуй умение зарабатывать (обучение)
  },
  "work_attitude": "Текст (1 предложение): Относится ли автор к работе как к благу или жалуется на нее?",
  "debt_status": "Текст (1 предложение): Есть ли признаки 'рабства долгов' или автор свободен?",
  "recommendations": [
     // 3 совета от лица Аркада
  ],
  "summary": "Краткий итог недели от мудреца Аркада (1 абзац)."
}
PROMPT;
    }
}
