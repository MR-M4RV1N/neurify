<?php

namespace App\Service;

use App\Entity\AiComment;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use HTMLPurifier;
use HTMLPurifier_Config;

class AiCommentService
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

    public function generateComment(Event $event): AiComment
    {
        $eventTitle = $event->getTitle();
        $eventDesc = $event->getDescription();
        $user = $event->getUser();

        if ($user === null) {
            throw new \Exception('User not found for the event.');
        }

        $userRole = $user->getArtisan();
        $userLang = $user->getLang();

        $eventRepo = $this->em->getRepository(Event::class);
        $allEvents = $eventRepo->count(['user' => $user]);
        $level = min((int) floor($allEvents / 10), 20);

        $date = $event->getDate()->format('Y-m-d');

        $prompt = $this->generateSherlockPrompt(
            $userLang,
            $userRole,
            $eventTitle,
            $eventDesc,
            $level,
            $date
        );

        $commentText = $this->fetchAiComment($prompt);
        $commentText = $this->purifier->purify($commentText);

        $aiComment = new AiComment();
        $aiComment->setEvent($event);
        $aiComment->setUser($user);
        $aiComment->setContent($commentText);
        $aiComment->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($aiComment);
        $this->em->flush();

        return $aiComment;
    }

    private function generateSherlockPrompt(
        string $userLang,
        string $userRole,
        string $eventTitle,
        string $eventDesc,
        int $level,
        string $date
    ): string {
        return <<<EOT
Neurify.life — платформа, где пользователи публикуют свои пройденные испытания и расширяют зону комфорта.

🗣 Ответ сгенерируй на языке: $userLang.

👤 Роль пользователя: $userRole  
🌟 Название испытания: $eventTitle  
📋 Описание: $eventDesc  
📈 Уровень пользователя на платформе: $level из 20  
📅 Дата публикации: $date

✍️ Напиши HTML-комментарий в стиле «Шерлок Холмс в мире геймификации» — с сарказмом, дедукцией и театральной наблюдательностью.

Формат: 4 логически связанных абзаца. Используй HTML-теги <p> для каждого абзаца.  
Избегай заголовков вроде «Вступление» или «Интерпретация».  
Не обсуждай описание профиля — комментируй только публикацию (название и описание события).  
Тон — остроумный, как будто ты — Шерлок Холмс, размышляющий вслух.  
Можешь упоминать Ватсона, использовать дедуктивные гипотезы, саркастические реплики, элементы театральности.  
Ответ должен быть не длиннее 600 символов.

Примеры фраз для вдохновения (если используешь русский):
— "Элементарно, Ватсон!"  
— "Что может скрываться за этой краткой фразой?"  
— "Не исключаю саботажа со стороны автора..."  
— "Пожалуй, это требует наблюдения..."

❗️Не используй Markdown-блоки с ```html — только чистые <p> теги.
EOT;
    }

    private function fetchAiComment(string $prompt): string
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
                        ['role' => 'system', 'content' => 'Ты остроумный ассистент, комментирующий приключения в стиле Шерлока Холмса.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 600,
                    'temperature' => 0.8,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('DeepSeek API error: ' . $response->getStatusCode() . ' — ' . $response->getContent(false));
            }

            $data = $response->toArray();
            return $data['choices'][0]['message']['content'] ?? 'Комментарий не удалось сгенерировать.';
        } catch (\Throwable $e) {
            throw new \RuntimeException('Ошибка при обращении к DeepSeek: ' . $e->getMessage(), 0, $e);
        }
    }

    private function generateMockComment(string $title, string $description): string
    {
        return sprintf(
            "Событие «%s» — отличный шаг! Оно демонстрирует готовность выходить за рамки и искать новое. Продолжай двигаться вперёд!",
            $title
        );
    }
}