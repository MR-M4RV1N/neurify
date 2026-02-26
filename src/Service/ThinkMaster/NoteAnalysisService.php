<?php

namespace App\Service\ThinkMaster;

use App\Entity\ThinkMaster\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class NoteAnalysisService
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
        // Разрешаем только безопасные теги, как в AiConsultingService
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,li');
        $config->set('URI.Disable', true);
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Генерация анализа и запись в $note->analysis
     */
    public function generateAnalysis(Note $note): void
    {
        $prompt = $this->buildPrompt($note);

        $html = $this->fetchAiAnalysis($prompt);
        $html = $this->purifier->purify($html);

        $note->setAnalysis($html);
        $note->setUpdatedAt(new \DateTimeImmutable());
        // persist/flush оставляем на контроллер
    }

    private function buildPrompt(Note $note): string
    {
        $user = $note->getUser();
        $userLang = $user && method_exists($user, 'getLang')
            ? (string) $user->getLang()
            : 'ru';

        $text = $this->normalize((string) $note->getText(), 1500);
        $next = $this->normalize((string) $note->getNext(), 600);
        $recs = $this->normalize((string) $note->getRecommendations(), 600);

        if ($next === '') {
            $next = 'None.';
        }
        if ($recs === '') {
            $recs = 'None.';
        }

        return <<<EOT
ThinkMaster is a tool for structured thinking and rumination analysis.

Answer language: {$userLang}. If Russian, address the user as "вы".

User note (raw thoughts):
{$text}

User next step (if any):
{$next}

User recommendations for AI (meta-comment, if any):
{$recs}

You are NOT a doctor. Do NOT use medical language or diagnoses.

Write compact HTML using ONLY: <p>, <strong>, <em>, <b>, <i>, <ul>, <li>. No links, no images, no tables.

Structure EXACTLY:
<p>1–3 sentences: короткий обзор, какая тема и эмоциональный фон у этой заметки.</p>
<p>1–3 sentences: что в этом мышлении помогает двигаться вперёд, а что создаёт застревание или хождение по кругу.</p>
<ul>
  <li>Наблюдение 1 (≤30 слов): конкретный паттерн мысли или вопрос, который повторяется или усиливает напряжение.</li>
  <li>Наблюдение 2 (≤30 слов): ещё один паттерн, связанный, например, с оценками себя, страхами или ожиданиями.</li>
  <li>Наблюдение 3 (≤30 слов): если новых паттернов нет, кратко подведите итоги анализа.</li>
</ul>
<p>1–3 sentences: как можно немного изменить угол зрения, чтобы сделать мышление более конструктивным и мягким к себе.</p>

Rules:
- Не давай диагнозы и не используй медицинские термины.
- Пиши уважительно, спокойно и поддерживающе.
- Не придумывай факты, которых нет в тексте; опирайся только на формулировки пользователя.
- Общий объём — до ~1200–1600 знаков, будь конкретен.

Return ONLY the HTML.
EOT;
    }

    private function fetchAiAnalysis(string $prompt): string
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
                    ['role' => 'system', 'content' => 'You are a careful, supportive thinking analyzer for a tool called ThinkMaster.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'max_tokens'  => 900,
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