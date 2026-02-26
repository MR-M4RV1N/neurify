<?php

namespace App\Service\GrowModels;

use App\Entity\AiCareerConsultingResult;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class AiSwotConsultingService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var string */
    private $deepseekApiKey;

    /** @var HTMLPurifier */
    private $purifier;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->deepseekApiKey = $deepseekApiKey;

        /** HTML Purifier config */
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,li');
        $config->set('URI.Disable', true);

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Генерация карьерного анализа (уровень по модели Дрейфуса).
     *
     * @param array<int,array{title:string,description:string}> $eventsPayload
     */
    public function generateCareerAnalysis(
        string $profileDescription,
        array $eventsPayload,
        User $user,
        ?AiCareerConsultingResult $previousResult = null
    ): AiCareerConsultingResult {

        // --------------------------
        // 1. Формируем краткий список событий
        // --------------------------
        $eventsBrief = $this->formatEventsForPrompt($eventsPayload);

        // --------------------------
        // 2. Summary предыдущего анализа
        // --------------------------
        $previousSummary = $previousResult ? $previousResult->getSummary() : 'None.';

        // --------------------------
        // 3. Создаём промпт
        // --------------------------
        $prompt = $this->buildCareerPrompt(
            $profileDescription,
            $eventsBrief,
            $previousSummary,
            $user->getLang()
        );

        // --------------------------
        // 4. Отправляем запрос в DeepSeek
        // --------------------------
        $json = $this->fetchCareerJson($prompt);

        // --------------------------
        // 5. Нормализуем JSON (защита от пустых ответов)
        // --------------------------
        if (!is_array($json)) {
            $json = [];
        }

        $json = array_merge([
            'career_level'   => 1,
            'level_name'     => 'Novice',
            'signals'        => [],
            'reasoning'      => '',
            'recommendations'=> [],
            'summary'        => '',
        ], $json);

        if (!is_array($json['signals'])) {
            $json['signals'] = [];
        }
        if (!is_array($json['recommendations'])) {
            $json['recommendations'] = [];
        }

        // --------------------------
        // 6. Генерируем HTML-отчёт
        // --------------------------
        $html = $this->safePurify(
            $this->renderCareerHtml($json)
        );

        // --------------------------
        // 7. Сохраняем результат
        // --------------------------
        $entity = new AiCareerConsultingResult();
        $entity->setUser($user);
        $entity->setCareerLevel((int) $json['career_level']);
        $entity->setLevelName((string) $json['level_name']);
        $entity->setSummary((string) $json['summary']);
        $entity->setGeneratedTasks(false);
        $entity->setAnalysisJson($json);
        $entity->setHtml($html);

        // Снимки контекста
        $entity->setEventsSnapshot($eventsPayload);
        $entity->setProfileSnapshot($profileDescription);

        if ($previousResult) {
            $entity->setPreviousLevel($previousResult->getCareerLevel());
        }

        $now = new \DateTimeImmutable();
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    // ======================
    // PROMPT
    // ======================
    private function buildCareerPrompt(
        string $profileDesc,
        string $eventsBrief,
        string $previousSummary,
        string $userLang
    ): string {
        return <<<EOT
You are a career consultant working with the Dreyfus model (levels 1–5).

Always write in the language specified below. If the language code is "ru", answer in Russian; if "en", answer in English; if "lv", answer in Latvian. If the code is unknown, choose the closest reasonable language.

[LANG]
{$userLang}

Dreyfus model for career (mandatory interpretation):
1 — Novice: does not understand what they want, has almost no experience, actions are chaotic.
2 — Advanced Beginner: has chosen a direction, there is some learning/courses, but little or no practical experience.
3 — Competent: already has practical experience, tasks, projects, first results.
4 — Proficient: works systematically in the profession, takes responsibility, helps others, sees the bigger picture.
5 — Expert: creates something new, influences the team/industry, teaches others, sets standards.

User data:

[PROFILE]
{$profileDesc}

[MONTH EVENTS]
{$eventsBrief}

[PREVIOUS ANALYSIS]
{$previousSummary}

Interpretation rules:
- If the text is "No recent events." it means there were no events in the last month; rely on the profile and previous analysis.
- If the previous analysis looks like "None." or is empty, assume there was no previous analysis.
- Even if the data is limited, you must still choose exactly one level from 1 to 5 and justify it.
- You are not allowed to leave the fields "reasoning", "signals", "recommendations" or "summary" empty.
- Try to extract as many meaningful signals as possible from any text (even if it is short).

Your task:
1) Determine the career level (1–5) strictly according to the model above.
2) Formulate a clear "reasoning" explaining why this specific level was chosen.
3) Highlight at least 3 "signals" — short phrases that show how you arrived at this conclusion.
   Examples of signals: "no events related to the profession", "courses exist but no practice", "regular tasks in the profession", "mentoring experience", "mentions of leadership/responsibility".
4) Create at least 3 concrete "recommendations" for the next month. Each recommendation must be one clear, practical sentence without fluff.
5) Formulate a short "summary" (2–3 sentences): who this person is now in career terms and what should be the next focus.

Required answer format — STRICT JSON (no comments, no surrounding text):

{
  "career_level": <integer from 1 to 5>,
  "level_name": "<Novice | Advanced Beginner | Competent | Proficient | Expert>",
  "signals": ["string 1", "string 2", "string 3", "..."],
  "reasoning": "connected explanation in 2–5 sentences, without line breaks",
  "recommendations": ["recommendation 1", "recommendation 2", "recommendation 3", "..."],
  "summary": "2–3 sentences briefly describing the current career status and the focus of the next step"
}

Additional requirements:
- "signals" must be an array with at least 3 non-empty strings.
- "recommendations" must be an array with at least 3 non-empty strings.
- "reasoning" and "summary" must not be empty strings.
- If the data is very limited, honestly say that the information is scarce, but still choose a level and give cautious yet concrete recommendations.
- Do not use line breaks inside JSON string values. One field — one line.
- No text outside the JSON. No backticks, no code fences, no explanations around the JSON.
EOT;
    }


    // ======================
    // Format events
    // ======================
    private function formatEventsForPrompt(array $events): string
    {
        if (!$events) {
            return 'No recent events.';
        }

        $lines = [];
        $i = 0;

        foreach ($events as $e) {
            $i++;
            $title = isset($e['title']) ? $this->normalize($e['title'], 100) : '';
            $desc  = isset($e['description']) ? $this->normalize($e['description'], 180) : '';

            $lines[] = $i . '. ' . $title . ' — ' . $desc;

            if ($i >= 10) { break; }
        }

        return implode("\n", $lines);
    }

    // ======================
    // Fetch JSON from DeepSeek
    // ======================
    private function fetchCareerJson(string $prompt): array
    {
        $url = 'https://api.deepseek.com/v1/chat/completions';

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                    'Content-Type'  => 'application/json'
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Ты — эксперт по карьерному росту. Отвечай строго JSON.'],
                        ['role' => 'user',   'content' => $prompt]
                    ],
                    'temperature' => 0.4,
                    'max_tokens'  => 1200
                ]
            ]);
        } catch (\Throwable $e) {
            return [];
        }

        try {
            $data = $response->toArray(false);
            $raw  = $data['choices'][0]['message']['content'] ?? '{}';

            $json = json_decode($raw, true);

            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    // ======================
    // Render HTML from JSON
    // ======================
    private function renderCareerHtml(array $json): string
    {
        $signalsHtml = '';
        foreach ($json['signals'] as $s) {
            $signalsHtml .= '<li>' . htmlspecialchars($s, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        $recHtml = '';
        foreach ($json['recommendations'] as $r) {
            $recHtml .= '<li>' . htmlspecialchars($r, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        $reasoning = htmlspecialchars($json['reasoning'], ENT_QUOTES, 'UTF-8');

        return "
            <p><strong>Карьерный уровень:</strong> {$json['level_name']} ({$json['career_level']})</p>
            <p>{$reasoning}</p>

            <p><strong>Сигналы:</strong></p>
            <ul>{$signalsHtml}</ul>

            <p><strong>Рекомендации:</strong></p>
            <ul>{$recHtml}</ul>
        ";
    }

    // ======================
    // Normalize text
    // ======================
    private function normalize($text, $limit)
    {
        $t = (string) $text;
        $t = strip_tags($t);
        $t = preg_replace('/\s+/u', ' ', $t);
        $t = trim($t);

        if (mb_strlen($t, 'UTF-8') > $limit) {
            $t = mb_substr($t, 0, $limit, 'UTF-8') . '…';
        }

        return $t;
    }

    // ======================
    // HTML Purifier wrapper
    // ======================
    private function safePurify(string $html): string
    {
        $html = $this->sanitizeUtf8($html);

        try {
            $clean = $this->purifier->purify($html);
            return $this->sanitizeUtf8($clean);
        } catch (\Throwable $e) {
            return '<p>' . htmlspecialchars(strip_tags($html)) . '</p>';
        }
    }

    // ======================
    // Fix UTF-8
    // ======================
    private function sanitizeUtf8(?string $text): string
    {
        if ($text === null) return '';

        $t = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $text);
        if ($t === null) $t = '';

        $t = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $t);
        if ($t === null) $t = '';

        $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $t);
        return $fixed !== false ? $fixed : '';
    }
}