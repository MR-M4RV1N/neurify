<?php

namespace App\Service\ThinkMaster;

use App\Entity\ThinkMaster\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class NoteAdviceService
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

        $config = HTMLPurifier_Config::createDefault();
        // Разрешаем только безопасные теги
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,li');
        $config->set('URI.Disable', true);
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Генерирует совет и записывает его в $note->advice
     */
    public function generateAdvice(Note $note): void
    {
        $prompt = $this->buildPrompt($note);

        $html = $this->fetchAiAdvice($prompt);
        $html = $this->purifier->purify($html);

        $note->setAdvice($html);
        $note->setUpdatedAt(new \DateTimeImmutable());
        // persist/flush — в контроллере
    }

    private function buildPrompt(Note $note): string
    {
        $user = $note->getUser();
        $userLang = $user && method_exists($user, 'getLang')
            ? (string) $user->getLang()
            : 'ru';

        $text  = $this->normalize((string) $note->getText(), 1500);
        $next  = $this->normalize((string) $note->getNext(), 600);
        $recs  = $this->normalize((string) $note->getRecommendations(), 600);
        $anal  = $this->normalize((string) $note->getAnalysis(), 1500);

        if ($next === '') {
            $next = 'None.';
        }
        if ($recs === '') {
            $recs = 'None.';
        }
        if ($anal === '') {
            $anal = 'None.'; // если анализа ещё нет — совет строим по заметке
        }

        return <<<EOT
ThinkMaster is a tool for structured thinking and rumination reduction.

Answer language: {$userLang}. If Russian, address the user as "вы".

User note (raw thoughts):
{$text}

User next step (if any):
{$next}

User recommendations for AI (meta-comment, if any):
{$recs}

Previous analysis (HTML stripped; if "None.", treat as absent):
{$anal}

You are NOT a doctor. Do NOT use medical language or diagnoses.
Your role: gently help user move from thinking loops to 1–3 конкретных шагов.

Write compact HTML using ONLY: <p>, <strong>, <em>, <b>, <i>, <ul>, <li>. No links, no images, no tables.

Structure EXACTLY:
<p>1–2 предложения: короткий мостик от размышлений к действиям — что сейчас главное направление фокуса.</p>
<ul>
  <li>Шаг 1 (≤32 слова): очень конкретное действие в ближайшие 24–48 часов, маленькое и выполнимое.</li>
  <li>Шаг 2 (≤32 слова): действие на ближайшую неделю, помогающее снизить застревание в одних и тех же мыслях.</li>
  <li>Шаг 3 (≤32 слова): шаг по заботе о себе (сон, нагрузка, контакт с людьми), связанный с темой заметки.</li>
</ul>
<p>1–2 предложения: что считать маленьким прогрессом и как не ругать себя, если получается неидеально.</p>

Rules:
- Используй предыдущий анализ, если он есть, как подсказку, но не переписывай его: твоя задача — практические шаги.
- Не придумывай факты — опирайся только на текст заметки и анализа.
- Пиши простым и понятным языком, без теории.
- Общий объём — до ~1000–1400 знаков, будь конкретен и доброжелателен.

Return ONLY the HTML.
EOT;
    }

    private function fetchAiAdvice(string $prompt): string
    {
        $url = 'https://api.deepseek.com/v1/chat/completions';

        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'       => 'deepseek-chat',
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a practical, gentle advisor for a tool called ThinkMaster.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'max_tokens'  => 800,
                'temperature' => 0.6,
            ],
            'timeout'      => 120,
            'max_duration' => 600,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'DeepSeek API error: ' .
                $response->getStatusCode() . ' — ' .
                $response->getContent(false)
            );
        }

        $data = $response->toArray(false);
        $text = isset($data['choices'][0]['message']['content'])
            ? (string) $data['choices'][0]['message']['content']
            : '';

        return $text !== '' ? $text : 'Failed to generate.';
    }

    private function normalize($text, $limit)
    {
        $t = (string) $text;
        $t = strip_tags($t);
        $t = preg_replace('/\s+/u', ' ', $t);
        $t = trim($t);
        if ($t === '') {
            return '';
        }
        if (mb_strlen($t, 'UTF-8') > $limit) {
            $t = mb_substr($t, 0, $limit, 'UTF-8') . '…';
        }
        return $t;
    }
}