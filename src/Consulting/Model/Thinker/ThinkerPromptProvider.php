<?php

namespace App\Consulting\Model\Thinker;

use DateTimeImmutable;

class ThinkerPromptProvider
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
PROMPT;
    }

    public function getSystemPrompt(string $style, string $lang): string
    {
        $base = $this->getBasePrompt();

        if ($style === 'berne') {
            return $this->getBernePrompt($base);
        }

        if ($style === 'taleb') {
            return $this->getTalebPrompt($base);
        }

        if ($style === 'jung') {
            return $this->getJungPrompt($base);
        }

        // Заглушка для остальных
        return $base;
    }

    public function getStyleShortLabel(string $style, string $lang): string
    {
        $labels = [
            'berne' => [
                'ru' => 'Эрик Берн (Транзакционный анализ)',
                'lv' => 'Ēriks Berns (Transakciju analīze)',
                'en' => 'Eric Berne (Transactional Analysis)',
            ],
            'taleb' => [
                'ru' => 'Нассим Талеб (Антихрупкость)',
                'lv' => 'Nasims Talebs (Antitrauslums)',
                'en' => 'Nassim Taleb (Antifragility)',
            ],
            'jung' => [
                'ru' => 'Карл Юнг (Аналитическая психология)',
                'lv' => 'Karls Jungs (Analītiskā psiholoģija)',
                'en' => 'Carl Jung (Analytical Psychology)',
            ],
        ];

        return $labels[$style][$lang] ?? $labels[$style]['ru'] ?? $style;
    }

    public function getStyleDescription(string $style, string $lang): string
    {
        $descriptions = [
            'berne' => [
                'ru' => 'Эрик Берн — создатель Транзакционного анализа. Этот метод рассматривает в личности три эго-состояния: Родитель (контроль/забота), Взрослый (логика/факты) и Ребенок (эмоции/творчество). Анализ поможет понять, из какой позиции вы действуете, в какие "игры" играете и какие жизненные сценарии проигрываете.',
                'lv' => 'Ēriks Berns ir transakciju analīzes izveidotājs. Šī metode aplūko personību caur trim ego stāvokļiem: Vecāks (kontrole/rūpes), Pieaugušais (loģika/fakti) un Bērns (emocijas/radošums). Analīze palīdzēs saprast, no kādas pozīcijas jūs rīkojaties, kādas "spēles" spēlējat un kādus dzīves scenārijus izspēlējat.',
                'en' => 'Eric Berne is the creator of Transactional Analysis. This method views personality through three ego states: Parent (control/care), Adult (logic/facts), and Child (emotions/creativity). The analysis helps understand which position you act from, what "games" you play, and what life scripts you enact.',
            ],
            'taleb' => [
                'ru' => 'Нассим Талеб — автор концепции Антихрупкости. Анализ (в разработке) будет искать уязвимости, "Черных лебедей" и способы стать сильнее через стресс.',
                'lv' => 'Nasims Talebs ir antitrausluma koncepcijas autors. Analīze (izstrādē) meklēs ievainojamības, "Melnos gulbjus" un veidus, kā kļūt stiprākam caur stresu.',
                'en' => 'Nassim Taleb is the author of Antifragility. The analysis (under development) will look for vulnerabilities, "Black Swans", and ways to gain strength through stress.',
            ],
            'jung' => [
                'ru' => 'Карл Юнг — основатель аналитической психологии. Анализ поможет исследовать Тень, Архетипы и бессознательные процессы.',
                'lv' => 'Karls Jungs — analītiskās psiholoģijas pamatlicējs. Analīze palīdzēs izpētīt Ēnu, Arhetipus un bezapziņas procesus.',
                'en' => 'Carl Jung — founder of analytical psychology. Analysis will help explore the Shadow, Archetypes, and unconscious processes.',
            ],
        ];

        return $descriptions[$style][$lang] ?? $descriptions[$style]['ru'] ?? '';
    }

    public function buildUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $style, string $lang): string
    {
        if ($style === 'berne') {
            return $this->buildBerneUserPrompt($weekStart, $weekText, $lang);
        }

        if ($style === 'taleb') {
            return $this->buildTalebUserPrompt($weekStart, $weekText, $lang);
        }

        if ($style === 'jung') {
            return $this->buildJungUserPrompt($weekStart, $weekText, $lang);
        }

        return ''; // Заглушка
    }

    // --- ERIC BERNE ---

    private function getBernePrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — эксперт по Транзакционному анализу Эрика Берна.
Твоя задача — проанализировать неделю автора через призму трех эго-состояний:
1. Родитель (Parent): Критикующий (правила, запреты) или Заботящийся (поддержка, опека).
2. Взрослый (Adult): Объективный анализ, факты, решение проблем "здесь и сейчас".
3. Ребенок (Child): Адаптивный (послушный/бунтующий) или Свободный (творчество, спонтанность).

Также ищи:
- Психологические игры (повторяющиеся паттерны с скрытыми мотивами).
- Сценарные решения (убеждения о себе и мире).
- Поглаживания (единицы признания).

Цель: Показать автору его баланс эго-состояний и подсветить неэффективные игры.
PROMPT;
    }

    private function buildBerneUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');
        $langMap = [
            'lv' => 'Valoda: latviešu',
            'en' => 'Language: English',
        ];
        $languageLine = $langMap[$lang] ?? 'Язык: русский';

        return <<<PROMPT
Проанализируй записи за неделю методом Эрика Берна.

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "thinker_style": "berne",
  "rating": 0, // Оценка 0-10: Насколько силён и стабилен был "Взрослый" (Adult) на этой неделе.
  "ego_states": {
    "parent": 0, // Примерный % времени/энергии в состоянии Родителя
    "adult": 0,  // Примерный % времени/энергии в состоянии Взрослого
    "child": 0   // Примерный % времени/энергии в состоянии Ребенка
    // Сумма должна быть 100
  },
  "games_played": [
    // Список замеченных "игр" или манипуляций (макс 3), например "Да, но...", "Жертва", "Спасатель"
  ],
  "key_transactions": [
    // 1-2 ключевые ситуации/диалога с разбором (кто из какой позиции общался)
  ],
  "recommendations": [
    // 3 конкретных совета, как усилить Взрослого или выйти из игр
  ],
  "summary": "Краткий итог недели на языке ТА (1 абзац)."
}

{$languageLine}
PROMPT;
    }

    // --- NASSIM TALEB ---

    private function getTalebPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — Нассим Талеб, эксперт по рискам, неопределенности и антихрупкости.
Твоя задача — проанализировать неделю автора через призму твоих ключевых концепций:
1. Антихрупкость: Выигрывает ли автор от стресса и хаоса? Или он хрупок (ломается) / неуязвим (просто стоит на месте)?
2. Via Negativa (Через отрицание): Улучшает ли автор жизнь, убирая лишнее (вредную еду, токсичных людей, шум), или пытается добавить что-то новое?
3. Шкура на кону (Skin in the Game): Несет ли автор ответственность за свои решения? Рискует ли своим?
4. Стратегия Штанги (Barbell): Сочетает ли автор крайнюю осторожность с крайним риском, избегая "теплой середины"?
5. Черные Лебеди: Были ли редкие, непредсказуемые события с сильным влиянием?

Тон: Прямой, скептичный к теориям, уважающий практиков и риск. Не "коучинговый", а философско-прагматичный.
PROMPT;
    }

    private function buildTalebUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');
        $langMap = [
            'lv' => 'Valoda: latviešu',
            'en' => 'Language: English',
        ];
        $languageLine = $langMap[$lang] ?? 'Язык: русский';

        return <<<PROMPT
Проанализируй записи за неделю как Нассим Талеб.

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "thinker_style": "taleb",
  "antifragility_score": 0, // 0-10: 0=Хрупкий, 5=Неуязвимый, 10=Антихрупкий (растет от стресса)
  "barbell_strategy": {
     "conservative": 0, // % Времени/усилий в зоне полной безопасности (рутина, прогулки, чтение классики)
     "aggressive": 0,   // % Времени/усилий в зоне высокого риска/высокой награды (стартапы, смелые идеи)
     "middle": 0        // % "Середина" (офисная работа, новости, пустая суета) - это плохо, должно быть мало
     // Сумма должна быть 100
  },
  "metrics": {
     "via_negativa": 0,      // 0-100%: Насколько автор преуспел в "отсечении лишнего"
     "skin_in_the_game": 0,  // 0-100%: Насколько слова автора подтверждены делом/риском
     "optionality": 0        // 0-100%: Насколько автор сохраняет свободу выбора и опциональность
  },
  "black_swans": [
     // Список неожиданных событий (если были). Если нет - пустой список.
     // Пример: "Внезапная встреча, изменившая планы", "Потеря данных"
  ],
  "recommendations": [
     // 3 совета в стиле Талеба (убрать хрупкость, добавить риск, игнорировать шум)
  ],
  "summary": "Краткий, едкий и точный итог недели в стиле Талеба (1 абзац)."
}

{$languageLine}
PROMPT;
    }


    // --- CARL JUNG ---

    private function getJungPrompt(string $base): string
    {
        return <<<PROMPT
{$base}

Ты — Карл Густав Юнг, основатель аналитической психологии.
Твоя задача — проанализировать неделю автора через призму твоих ключевых концепций:
1. ИНДИВИДУАЦИЯ: Движется ли автор к целостности? Есть ли баланс межу Эго (сознанием) и Самостью (центром психики)?
2. ТЕНЬ: Что автор подавляет или игнорирует? Есть ли проекции (раздражение на других, которое отражает собственные скрытые черты)?
3. АРХЕТИПЫ: Какие роли играл автор? (Герой, Мудрец, Исследователь, Бунтарь, Творец, Правитель, Заботливый, Любовник, Шут, Славный малый, Невинный, Маг). Выдели 1-2 доминирующих.
4. СИНХРОНИЧНОСТЬ: Были ли значимые совпадения?
5. СНЫ и СИМВОЛЫ: Если есть упоминания снов или ярких образов, проанализируй их.

Тон: Глубокий, аналитический, мистический, но приземленный к реальности. Используй термины "Бессознательное", "Интеграция Тени", "Самость".
PROMPT;
    }

    private function buildJungUserPrompt(DateTimeImmutable $weekStart, string $weekText, string $lang): string
    {
        $weekEnd = $weekStart->modify('+6 days');
        $langMap = [
            'lv' => 'Valoda: latviešu',
            'en' => 'Language: English',
        ];
        $languageLine = $langMap[$lang] ?? 'Язык: русский';

        return <<<PROMPT
Проанализируй записи за неделю как Карл Юнг.

ПЕРИОД: {$weekStart->format('Y-m-d')} — {$weekEnd->format('Y-m-d')}

ДНЕВНИК:
{$weekText}

ФОРМАТ JSON (СТРОГО):
{
  "thinker_style": "jung",
  "individuation_level": 0, // 0-10: Насколько гармоничен баланс Эго и Самости. 10 = полная целостность.
  "shadow_analysis": {
     "score": 0, // 0-100%: Уровень интеграции тени (чем выше, тем лучше автор осознает и принимает свои скрытые стороны)
     "description": "Краткое описание того, какая Тень проявилась (подавленные эмоции, проекции) и как с ней работать."
  },
  "archetypes": [
     // 1-2 самых активных архетипа на этой неделе
     {"name": "Название (Герой/Мудрец...)", "description": "Как именно проявился этот архетип в действиях автора"}
  ],
  "balance": {
     "inner_world": 0, // % Внимания к снам, интуиции, чувствам, рефлексии
     "outer_world": 0  // % Внимания к фактам, действиям, социуму, работе
     // Сумма должна быть 100
  },
  "synchronicity": "Описание значимых совпадений или символов (если были). Если нет - null.",
  "recommendations": [
     // 3 совета в стиле Юнга (обратить внимание на сны, интегрировать тень, найти баланс)
  ],
  "summary": "Глубокий и проницательный итог недели в стиле Юнга (1 абзац)."
}

{$languageLine}
PROMPT;
    }
}
