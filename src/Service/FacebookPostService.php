<?php

namespace App\Service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class FacebookPostService
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

        // Для Facebook нам не нужен HTML — чистим всё.
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Сгенерировать текст поста для Facebook на основе события и биографии пользователя.
     * Возвращает чистый текст без HTML.
     */
    public function generatePost(Event $event, string $aiHint = ''): string
    {
        $user = $event->getUser();
        if ($user === null) {
            throw new \RuntimeException('User not found for the event.');
        }

        // Безопасные геттеры под твой PHP 7.3
        $eventTitle = (string) $event->getTitle();
        $eventDesc  = (string) $event->getDescription();

        // Поддержка обоих вариантов: getDescription() и description()
        $userBio = '';
        if (method_exists($user, 'getDescription')) {
            $userBio = (string) $user->getDescription();
        } elseif (method_exists($user, 'description')) {
            $userBio = (string) $user->description();
        }

        // Язык и «уровень» как в твоём примере (можно убрать, если не нужно)
        $userLang = method_exists($user, 'getLang') ? (string) $user->getLang() : 'ru';
        $eventRepo = $this->em->getRepository(get_class($event));
        // если у тебя Event::class — оставь как есть:
        // $eventRepo = $this->em->getRepository(Event::class);
        $allEvents = method_exists($eventRepo, 'count') ? (int) $eventRepo->count(['user' => $user]) : 0;
        $level = min((int) floor($allEvents / 10), 20);

        $date = $event->getDate() ? $event->getDate()->format('Y-m-d') : date('Y-m-d');

        $prompt = $this->buildFacebookPrompt(
            $userLang,
            $eventTitle,
            $eventDesc,
            $userBio,
            $level,
            $date,
            $aiHint
        );

        $raw = $this->fetchAiText($prompt);
        $clean = $this->normalizeForFacebook($raw);

        // Дополнительные «страховочные» ограничения длины и эмодзи/хэштегов
        $final = $this->postprocess($clean, [
            'max_length' => 1200,   // целевой размер поста (Facebook допускает больше)
            'append_hashtags' => true,
            'hashtags' => $this->suggestHashtags($eventTitle, $eventDesc),
        ]);

        return $final;
    }

    /**
     * Формируем промпт под Facebook-пост: сторителлинг + ценность + CTA + хэштеги.
     */
    private function buildFacebookPrompt(
        string $lang,
        string $eventTitle,
        string $eventDesc,
        string $userBio,
        int $level,
        string $date,
        string $aiHint = ''
    ): string {

        $extraInstructions = trim($aiHint) !== ''
            ? "Дополнительные пожелания автора:\n\"$aiHint\"\n\nПрименяй их мягко: учитывай тон, фокус или нюансы, но не нарушай структуру поста и не ухудшай читаемость."
            : "Дополнительные пожелания отсутствуют. Используй стандартный стиль вовлекающего SMM-поста.";

        return <<<EOT
Ты — креативный SMM-копирайтер для Facebook.

$extraInstructions

Напиши сильный, живой и читаемый пост, который:
1) Кратко вводит в контекст (о чём событие: "$eventTitle"),
2) Раскрывает суть и пользу для читателя (исходя из "$eventDesc"),
3) Добавляет личную ноту или экспертность автора (кратко из "$userBio"),
4) Содержит чёткий призыв к действию (например: «Поделись опытом», «Задай вопрос»),
5) Пишется на языке: $lang,
6) Имеет длину 400–700 символов, без "воды" и повторов,
7) Оформлен как обычный текст — без HTML, Markdown, заголовков и хэштегов.

Дополнительные правила:
— Избегай клише вроде «всем привет».
— Не используй заглавные буквы для всего предложения.
— Хэштеги не используй в самом тексте (они будут добавлены позже).
— Начинай предложение с большой буквы.
— Эмодзи не используй внутри текста.

Входные данные:
— Название события: "$eventTitle"
— Описание события: "$eventDesc"
— Био автора: "$userBio"
— Дата события: $date
— Уровень автора на платформе Neurify: $level из 20

Выведи только готовый текст поста, без кавычек, без пояснений и без предисловий.
EOT;
    }

    /**
     * Вызов DeepSeek (можешь заменить модель и эндпоинт при необходимости).
     */
    private function fetchAiText(string $prompt): string
    {
        $url = 'https://api.deepseek.com/v1/chat/completions';

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Ты опытный SMM-копирайтер для Facebook. Пишешь кратко, вовлекающе, без HTML и Markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 800,
                    'temperature' => 0.8,
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('DeepSeek API error: ' . $response->getStatusCode() . ' — ' . $response->getContent(false));
            }

            $data = $response->toArray();
            $text = isset($data['choices'][0]['message']['content']) ? (string) $data['choices'][0]['message']['content'] : '';
            return $text !== '' ? $text : 'Не удалось сгенерировать пост.';
        } catch (\Throwable $e) {
            // Фоллбэк (на всякий случай)
            return $this->fallbackPost();
        }
    }

    /**
     * Чистка под Facebook: без HTML, обрезаем пробелы, нормализуем переносы.
     */
    private function normalizeForFacebook(string $text): string
    {
        $text = (string) $text;
        // Удаляем HTML-теги, если внезапно пришли
        $text = strip_tags($text);
        $text = $this->purifier->purify($text);
        // Нормализуем переносы и пробелы
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace("/[ \t]+/", " ", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        return trim($text);
    }

    /**
     * Финальная подрезка текста и добавление хэштегов.
     *
     * @param array{max_length?:int,append_hashtags?:bool,hashtags?:string[]} $options
     */
    private function postprocess(string $text, array $options = []): string
    {
        $maxLen = isset($options['max_length']) ? (int) $options['max_length'] : 1200;
        if (mb_strlen($text) > $maxLen) {
            $text = mb_substr($text, 0, $maxLen - 1) . '…';
        }

        if (!empty($options['append_hashtags']) && !empty($options['hashtags']) && is_array($options['hashtags'])) {
            $tags = array_slice(array_unique(array_filter(array_map([$this, 'normalizeHashtag'], $options['hashtags']))), 0, 6);
            if (!empty($tags)) {
                $text .= "\n\n" . implode(' ', $tags);
            }
        }

        return $text;
    }

    /**
     * Предложить хэштеги из названия и описания.
     * Очень простая эвристика (можно заменить на ML/LLM, если захочешь).
     *
     * @return string[]
     */
    private function suggestHashtags(string $title, string $desc): array
    {
        $base = mb_strtolower($title . ' ' . $desc);
        // Простейшие ключевые слова/токены
        $candidates = preg_split('/[^\p{L}\p{N}]+/u', $base, -1, PREG_SPLIT_NO_EMPTY);
        $candidates = array_filter($candidates, function ($w) {
            return mb_strlen($w) >= 4; // отсекаем совсем короткие
        });

        // Приоритетные добавки: бренд-теги
        $bonus = ['Neurify', 'ChallengeClub', 'ВыходИзЗоныКомфорта'];

        // Берём топ-5 по частоте
        $freq = [];
        foreach ($candidates as $w) {
            $freq[$w] = isset($freq[$w]) ? $freq[$w] + 1 : 1;
        }
        arsort($freq);
        $top = array_slice(array_keys($freq), 0, 5);

        return array_merge($top, $bonus);
    }

    /**
     * #слово -> нормализуем символы, пробелы и транслитерацию по минимуму.
     */
    private function normalizeHashtag(string $raw): string
    {
        $w = trim($raw);
        // Убираем недопустимые символы для хэштегов
        $w = preg_replace('/[^\p{L}\p{N}_]+/u', '', $w);
        if ($w === '') {
            return '';
        }
        // Первая буква — '#'
        if ($w[0] !== '#') {
            $w = '#' . $w;
        }
        return $w;
    }

    private function fallbackPost(): string
    {
        return "Сегодня сделал шаг из зоны комфорта — коротко, по делу и с пользой. Поделись в комментариях своим опытом и что тебя мотивирует двигаться дальше 💪";
    }
}