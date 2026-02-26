<?php

namespace App\Service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class AiAdviceService
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
        $config->set('HTML.Allowed', 'p,strong,em,b,i,ul,li,a[href]');
        $this->purifier = new HTMLPurifier($config);
    }

    public function generateAdvice(Event $event): string
    {
        $description = $event->getDescription();
        $user = $event->getUser();

        if ($user === null) {
            throw new \Exception('User not found for the event.');
        }

        if (!$description) {
            return 'Описание события пустое.';
        }

        $lang = $user->getLang() ?? 'ru';
        $prompt = $this->buildPrompt($description, $lang);

        $adviceText = $this->fetchAdvice($prompt);
        $adviceText = $this->purifier->purify($adviceText);

        return $adviceText;
    }

    private function buildPrompt(string $description, string $lang): string
    {
        return <<<EOT
Neurify.life — это платформа для личного развития, где пользователи публикуют события, связанные с испытаниями и достижениями.

🗣 Ответ сгенерируй на языке: $lang.

Вот описание события:
"$description"

Проанализируй это описание и предложи один конкретный и полезный совет по улучшению его содержания, чтобы оно было более информативным и мотивирующим. Формат — один абзац до 400 символов, можно использовать HTML (<p>, <b>, <i>).
EOT;
    }

    private function fetchAdvice(string $prompt): string
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
                        ['role' => 'system', 'content' => 'Ты профессиональный редактор, помогающий людям улучшать описание своих достижений.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 400,
                    'temperature' => 0.7,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('DeepSeek API error: ' . $response->getStatusCode() . ' — ' . $response->getContent(false));
            }

            $data = $response->toArray();
            return $data['choices'][0]['message']['content'] ?? 'Совет не удалось получить.';
        } catch (\Throwable $e) {
            return 'Ошибка при обращении к AI: ' . $e->getMessage();
        }
    }
}