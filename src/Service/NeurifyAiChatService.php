<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Ensemble;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;
use League\CommonMark\CommonMarkConverter;

class NeurifyAiChatService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var string */
    private $deepseekApiKey;

    /** @var HTMLPurifier */
    private $purifier;

    /** @var string */
    private $projectDir;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->deepseekApiKey = $deepseekApiKey;

        // Корень проекта (без параметров DI, чтобы не трогать services.yaml)
        $this->projectDir = dirname(__DIR__, 2);

        // HTML Purifier config
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,ol,li,br,span');
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('URI.Disable', true);

        $this->purifier = new HTMLPurifier($config);
    }

    private function decidePromptMode(string $userMessage, array $context): string
    {
        $system = <<<TXT
Ты — логический модуль-посредник.  
Твоя задача — определить, нужен ли аналитический режим (развитие, GROW, следующие шаги)  
или обычный информационный режим (философия, интерфейс, описание платформы).

Если вопрос связан с развитием, самоопределением, следующими шагами, уровнем, прогрессом,  
испытаниями, выбором задач или улучшением — верни ответ: use_analytics.

Если вопрос о платформе, её философии, структуре, интерфейсе, правилах, пользовании —  
верни ответ: use_information.

Ответ строго одним словом: use_analytics или use_information.
TXT;

        $payload = [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => "Вопрос: " . $userMessage],
            ]
        ];

        try {
            $response = $this->httpClient->request('POST', 'https://api.deepseek.com/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload
            ]);

            $data = json_decode($response->getContent(false), true);
            $decision = strtolower(trim($data['choices'][0]['message']['content'] ?? ''));

            return in_array($decision, ['use_analytics','use_information'])
                ? $decision
                : 'use_information';

        } catch (\Throwable $e) {
            return 'use_information';
        }
    }

    /**
     * Основной метод общения с AI-гидом Neurify.
     *
     * @param string $message  Сообщение пользователя
     * @param string $mode     'lite' | 'pro'
     * @param array  $history  [{"role":"user","content":"..."}, ...]
     * @param User   $user
     *
     * @return string|null
     */
    public function sendMessage(string $message, string $mode, array $history, User $user): ?string
    {
        $cleanUserMessage = trim($this->purifier->purify($message));
        if ($cleanUserMessage === '') {
            return null;
        }

        if ($mode === 'pro') {
            // 1. Посредник решает, что включить
            $decision = $this->decidePromptMode($cleanUserMessage, []);
            if ($decision === 'use_analytics') {
                // 2. Только теперь загружаем контекст GROW
                $ctx = $this->buildCoachingContext($user);
                // 3. И только теперь строим аналитический промпт
                $systemPrompt = $this->buildCoachingSystemPrompt($ctx);
            } else {
                // 4. Обычный информационный режим
                $systemPrompt = $this->buildInformationalPrompt();
            }
        } else {
            // Lite-режим всегда информационный
            $systemPrompt = $this->buildInformationalPrompt();
        }

        // ======================
        // Запрос к DeepSeek
        // ======================
        $payload = [
            'model' => 'deepseek-chat',
            'messages' => array_merge(
                [
                    ['role' => 'system', 'content' => $systemPrompt]
                ],
                $history,
                [
                    ['role' => 'user', 'content' => $cleanUserMessage]
                ]
            )
        ];

        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => $payload,
                    'timeout' => 30,
                ]
            );

            $data = json_decode($response->getContent(false), true);

            if (!isset($data['choices'][0]['message']['content'])) {
                return null;
            }

            $raw = $data['choices'][0]['message']['content'];
            // 1. Markdown → HTML
            $converter = new CommonMarkConverter([
                'html_input' => 'allow',     // позволяем HTML
                'allow_unsafe_links' => false,
            ]);
            $html = $converter->convertToHtml($raw);
            // 2. Чистим HTML (разрешены p, ul, li, strong, em)
            $clean = $this->purifier->purify($html);
            return trim($clean);

        } catch (\Throwable $e) {
            // Можно добавить логирование, если нужно
            return "Произошла ошибка при обращении к ИИ. Попробуйте позже.";
        }
    }

    // ============================================================
    //           COACHING: АНАЛИТИКА GROW + CAREER LEVELS
    // ============================================================

    /**
     * Собираем контекст для коучингового режима:
     * - есть ли категория GROW
     * - какие события в ней есть
     * - какие модели из career_levels.json уже "покрыты" событиями
     */
    private function buildCoachingContext(User $user): array
    {
        // 1. Ищем Ensemble "GROW" пользователя
        $grow = $this->em->getRepository(Ensemble::class)
            ->findOneBy([
                'author' => $user,
                'title'  => 'GROW',
            ]);

        if (!$grow) {
            return [
                'status'          => 'no_grow',
                'message'         => 'Пользователь ещё не создал категорию GROW.',
                'level_name'      => 'Zero',
                'title'           => 'Начальная ориентация',
                'description'     => 'У пользователя нет базовой категории GROW, с которой начинается системное развитие.',
                'completed_models'=> [],
                'missing_models'  => [],
            ];
        }

        // 2. Берём события из GROW
        $events = $this->em->getRepository(Event::class)
            ->findBy(['ensemble' => $grow]);

        $titles = array_map(function ($ev) {
            /** @var Event $ev */
            return mb_strtolower($ev->getTitle());
        }, $events);

        // 3. Загружаем уровни из career_levels.json
        $levels = $this->loadCareerLevels();

        if (empty($levels['levels'])) {
            return [
                'status'          => 'no_levels',
                'message'         => 'Файл career_levels.json не найден или пуст.',
                'level_name'      => 'Unknown',
                'title'           => 'Нет данных уровней',
                'description'     => 'Система уровней не инициализирована.',
                'completed_models'=> [],
                'missing_models'  => [],
            ];
        }

        // Пока берём уровень 0 ("Zero") как стартовый
        $levelIndex = 0;
        $levelInfo  = $levels['levels'][$levelIndex];

        $completed = [];
        $missing   = [];

        foreach ($levelInfo['models'] as $model) {

            $modelName = mb_strtolower($model['name']);
            $modelCode = mb_strtolower($model['code']);

            $found = false;

            foreach ($titles as $t) {
                if (
                    mb_stripos($t, $modelName) !== false ||
                    mb_stripos($t, $modelCode) !== false
                ) {
                    $found = true;
                    break;
                }
            }

            if ($found) {
                $completed[] = $model;
            } else {
                $missing[] = $model;
            }
        }

        return [
            'status'          => 'ok',
            'level_id'        => $levelInfo['id'],
            'level_name'      => $levelInfo['name'],
            'title'           => $levelInfo['title'],
            'description'     => $levelInfo['description'],
            'completed_models'=> $completed,
            'missing_models'  => $missing,
        ];
    }

    /**
     * Строим комбинированный системный промпт для режима "coaching":
     * - всегда ведём себя как обычный гид
     * - но при вопросах о развитии можно использовать аналитический блок
     */
    private function buildCoachingSystemPrompt(array $ctx): string
    {
        // Базовый промпт гида (такой же, как в information-режиме, но с инструкцией про аналитику)
        $prompt = <<<TXT
Ты — AI-гид платформы Neurify.life.
Твоя философия основана на нейропластичности: мозг развивается тогда, когда человек выходит за рамки привычного. Нейронные связи растут, когда в жизни появляется триада «Новое — Необычное — Страшное». Привычность, рутина и однообразие сужают нейронные пути и ограничивают восприятие мира. Новизна же создаёт новые маршруты мышления, расширяет возможности, открывает идеи и помогает человеку видеть больше, чем он видел раньше.
Основная идея Neurify.life:  
человек становится сильнее, свободнее и умнее, когда перестаёт жить автоматически и сознательно добавляет в жизнь новые действия, неожиданные шаги, смелые решения и маленькие преодоления. Каждый день должен содержать что-то новое, необычное или слегка страшное — это и есть топливо роста.
Ты помогаешь пользователю обнаруживать привычные нейронные «тропинки», находить новые направления и расширять карту жизни.  
Ты вдохновляешь, поддерживаешь, направляешь мягко и без давления.  
Отвечай ясным, дружелюбным, вдохновляющим стилем.  
Используй философию Neurify, когда объясняешь смысл развития, даёшь рекомендации, предлагаешь испытания или помогаешь найти путь вперёд.

Не используй HTML кроме <p>, <ul>, <li>, <strong>, <em>.

Важно: на платформе Neurify пользователь НЕ создаёт планы и НЕ пишет задачи заранее.
Он публикует только уже выполненные испытания и реальные шаги, которые он сделал.
Категория GROW — это журнал пройденных этапов развития, а не список предстоящих заданий.
Поэтому никогда не проси пользователя добавлять задачи в GROW.
Если требуется предложить шаг — формулируй его так, чтобы он был приглашением к действию,
но не как задание, которое он должен записать в платформу.

Если пользователь спрашивает о развитии, шагах, росте, карьере, уровне,
следующих шагах, прогрессе или испытаниях GROW — используй аналитические
данные ниже, чтобы дать более точные рекомендации.

Если вопрос общий (что за платформа, как работает интерфейс, где что находится),
просто отвечай как обычный гид, почти не упоминая аналитику.

=== АНАЛИТИКА ПОЛЬЗОВАТЕЛЯ ===
TXT;

        // Если нет GROW
        if ($ctx['status'] === 'no_grow') {
            $prompt .= "У пользователя ещё нет категории GROW.\n";
            $prompt .= "Объясни, зачем нужна категория GROW и предложи 1–2 простых шага по её созданию.\n";
            return $prompt;
        }

        if ($ctx['status'] === 'no_levels') {
            $prompt .= "Система уровней временно недоступна. Отвечай как обычный коуч без ссылок на уровни.\n";
            return $prompt;
        }

        // Есть данные по уровню и моделям
        $prompt .= "Текущий уровень: {$ctx['title']} ({$ctx['level_name']})\n";
        $prompt .= "Описание уровня:\n{$ctx['description']}\n\n";

        $prompt .= "Модели, которые пользователь уже коснулся (по его событиям в категории GROW):\n";
        if (count($ctx['completed_models']) === 0) {
            $prompt .= "- Пока ни одна модель явно не отражена в событиях.\n";
        } else {
            foreach ($ctx['completed_models'] as $m) {
                $prompt .= "- {$m['name']}\n";
            }
        }

        $prompt .= "\nМодели, которые ещё не реализованы в событиях пользователя и могут стать следующими шагами:\n";
        if (count($ctx['missing_models']) === 0) {
            $prompt .= "- Все базовые модели этого уровня уже представлены в событиях пользователя.\n";
        } else {
            foreach ($ctx['missing_models'] as $m) {
                $prompt .= "- {$m['name']}: {$m['text']}\n";
            }
        }

        $prompt .= "\nИспользуй эти данные только тогда, когда пользователь интересуется развитием.\n";
        $prompt .= "Дай не более 3 конкретных следующих шагов, мягко и поддерживающе.\n";

        return $prompt;
    }

    /**
     * Загрузка career_levels.json
     */
    private function loadCareerLevels(): array
    {
        $jsonPath = $this->projectDir . '/src/Data/career_levels.json';

        if (!file_exists($jsonPath)) {
            return [];
        }

        $json = file_get_contents($jsonPath);

        return json_decode($json, true);
    }

    private function buildInformationalPrompt(): string
    {
        return <<<TXT
Ты — AI-гид платформы Neurify.life.
Твоя философия основана на нейропластичности: мозг развивается тогда, когда человек выходит за рамки привычного. Нейронные связи растут, когда в жизни появляется триада «Новое — Необычное — Страшное». Привычность, рутина и однообразие сужают нейронные пути и ограничивают восприятие мира. Новизна же создаёт новые маршруты мышления, расширяет возможности, открывает идеи и помогает человеку видеть больше.

Основная идея Neurify.life:
человек становится сильнее, свободнее и умнее, когда перестаёт жить автоматически и сознательно добавляет в жизнь новые действия, неожиданные шаги, смелые решения и маленькие преодоления.

Ты вдохновляешь, поддерживаешь, направляешь мягко и без давления.
Отвечай ясным, дружелюбным, вдохновляющим стилем.
Используй философию Neurify естественно, когда объясняешь путь развития.

Не используй HTML кроме <p>, <ul>, <li>, <strong>, <em>.

Важно: на платформе Neurify пользователь НЕ создаёт планы и НЕ пишет задачи заранее.
Он публикует только уже выполненные испытания и реальные шаги, которые он сделал.
Категория GROW — это журнал пройденных этапов развития, а не список предстоящих заданий.

Если пользователь просит оценить его уровень или дать рекомендации по развитию, то надо ему сказать, чтобы он переключился в PRO-режим, где есть аналитика по его событиям. Или открыть главную страницу, открыть раздел с "Консультации" и там нажать кнопку "Проанализировать"
TXT;
    }
}