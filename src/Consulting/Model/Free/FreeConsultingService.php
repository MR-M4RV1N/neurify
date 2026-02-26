<?php

namespace App\Consulting\Model\Free;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FreeConsultingService implements ConsultingModelInterface
{
    private $em;
    private $httpClient;
    private $logger;
    private $requestStack;
    private $deepseekApiKey;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        RequestStack $requestStack,
        string $deepseekApiKey
    ) {
        $this->em = $em;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->deepseekApiKey = $deepseekApiKey;
    }

    public function getKey(): string
    {
        return 'free';
    }

    /**
     * Free доступен всегда, но фактически
     * будет отфильтрован пустым prompt-ом
     */
    public function supports(User $user): bool
    {
        return trim($this->getUserPrompt()) !== '';
    }

    public function generate(User $user, string $lang): ConsultingInsight
    {
        $userPrompt = $this->getUserPrompt();

        $eventsSnapshot = [
            'user_prompt' => $userPrompt,
            'user_description' => (string)$user->getDescription(),
        ];

        $prompt = $this->buildPrompt($userPrompt, $user);

        $rawJson = $this->fetchJson($prompt);
        $analysis = $this->normalize($rawJson);
        $html = $this->buildHtml($analysis);

        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary'] ?: 'Рекомендации сформированы');
        $insight->setAnalysisJson($analysis);
        $insight->setEventsSnapshot($eventsSnapshot);
        $insight->setGeneratedTasks(false);
        $insight->setHtml($html);

        $now = new \DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    // =========================
    // INTERNALS
    // =========================

    private function getUserPrompt(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return '';
        }

        return trim((string)$request->request->get('free_prompt'));
    }

    private function buildPrompt(string $userPrompt, User $user): string
    {
        $description = trim((string)$user->getDescription());

        return <<<PROMPT
Ты — внимательный, нейтральный AI-консультант.

Контекст пользователя (описание профиля):
{$description}

Запрос пользователя:
{$userPrompt}

Дай рекомендации строго в рамках запроса пользователя.
НЕ навязывай саморазвитие, если пользователь его не просит.

Верни результат в формате JSON:
{
  "focus": "ключевой фокус",
  "recommendations": ["рекомендация 1", "рекомендация 2"],
  "summary": "краткий итог"
}
PROMPT;
    }

    private function fetchJson(string $prompt): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a helpful assistant that outputs JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.6,
                        'response_format' => ['type' => 'json_object'],
                    ],
                    'timeout' => 60,
                ]
            );

            if ($response->getStatusCode() !== 200) {
                $this->logger->error('FreeConsulting: API error', [
                    'status' => $response->getStatusCode(),
                ]);
                return [];
            }

            $data = $response->toArray(false);
            $content = $data['choices'][0]['message']['content'] ?? '';

            if (!$content) {
                return [];
            }

            return json_decode($content, true) ?: [];

        } catch (\Throwable $e) {
            $this->logger->error('FreeConsulting exception', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    private function normalize(array $json): array
    {
        if (empty($json)) {
            return [
                'focus' => 'Ошибка',
                'recommendations' => [],
                'summary' => 'Не удалось получить ответ от нейросети. Попробуйте позже.',
            ];
        }

        return [
            'focus' => $json['focus'] ?? '',
            'recommendations' => $json['recommendations'] ?? [],
            'summary' => $json['summary'] ?? 'Рекомендации сформированы.',
        ];
    }

    private function buildHtml(array $data): string
    {
        if (
            empty($data['recommendations']) &&
            $data['summary'] === 'Не удалось получить ответ от нейросети. Попробуйте позже.'
        ) {
            return '<div class="alert alert-warning">Сервис временно недоступен.</div>';
        }

        $html = '';

        if (!empty($data['focus'])) {
            $html .= '<p><strong>Фокус:</strong> ' .
                htmlspecialchars($data['focus'], ENT_QUOTES, 'UTF-8') .
                '</p>';
        }

        if (!empty($data['summary'])) {
            $html .= '<p>' .
                nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) .
                '</p>';
        }

        if (!empty($data['recommendations'])) {
            $html .= '<p><strong>Рекомендации:</strong></p><ul>';

            foreach ($data['recommendations'] as $rec) {
                $html .= '<li>' .
                    htmlspecialchars($rec, ENT_QUOTES, 'UTF-8') .
                    '</li>';
            }

            $html .= '</ul>';
        }

        return $html;
    }
}