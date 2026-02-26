<?php

namespace App\Consulting\Model\Dreyfus;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\ConsultingInsight;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DreyfusConsultingService implements ConsultingModelInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var string */
    private $deepseekApiKey;

    /** @var DreyfusContextBuilder */
    private $contextBuilder;

    /** @var DreyfusPromptBuilder */
    private $promptBuilder;

    /** @var DreyfusResultNormalizer */
    private $resultNormalizer;

    /** @var DreyfusHtmlBuilder */
    private $htmlBuilder;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        string $deepseekApiKey,
        DreyfusContextBuilder $contextBuilder,
        DreyfusPromptBuilder $promptBuilder,
        DreyfusResultNormalizer $resultNormalizer,
        DreyfusHtmlBuilder $htmlBuilder
    ) {
        $this->em               = $em;
        $this->httpClient       = $httpClient;
        $this->deepseekApiKey   = $deepseekApiKey;
        $this->contextBuilder   = $contextBuilder;
        $this->promptBuilder    = $promptBuilder;
        $this->resultNormalizer = $resultNormalizer;
        $this->htmlBuilder      = $htmlBuilder;
    }

    /**
     * Уникальный ключ модели
     */
    public function getKey(): string
    {
        return 'dreyfus';
    }

    /**
     * Dreyfus применим всегда
     */
    public function supports(User $user): bool
    {
        return true;
    }

    /**
     * Генерация Dreyfus-инсайта
     */
    public function generate(User $user, string $lang): ConsultingInsight
    {
        // --------------------------
        // 1. Контекст пользователя
        // --------------------------
        $context = $this->contextBuilder->build($user);

        // --------------------------
        // 2. Prompt
        // --------------------------
        $prompt = $this->promptBuilder->build(
            $user,
            $lang,
            $context['eventsBrief']
        );

        // --------------------------
        // 3. Запрос к AI
        // --------------------------
        $rawJson = $this->fetchCareerJson($prompt);

        // --------------------------
        // 4. Нормализация результата
        // --------------------------
        $analysis = $this->resultNormalizer->normalize($rawJson);

        // --------------------------
        // 5. HTML (UI-контракт)
        // --------------------------
        $html = $this->htmlBuilder->build($analysis);

        // --------------------------
        // 6. Сохранение инсайта
        // --------------------------
        $insight = new ConsultingInsight();
        $insight->setUser($user);
        $insight->setModel($this->getKey());
        $insight->setSummary($analysis['summary']);
        $insight->setAnalysisJson($analysis);
        $insight->setHtml($html);
        $insight->setGeneratedTasks(false);
        $insight->setProfileSnapshot($context['profile']);
        $insight->setEventsSnapshot($context['eventsSnapshot']);

        $now = new \DateTimeImmutable();
        $insight->setCreatedAt($now);
        $insight->setUpdatedAt($now);

        $this->em->persist($insight);
        $this->em->flush();

        return $insight;
    }

    // ======================
    // AI helpers
    // ======================

    private function fetchCareerJson(string $prompt): array
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.deepseek.com/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Return ONLY valid JSON.'],
                            ['role' => 'user',   'content' => $prompt],
                        ],
                        'temperature' => 0.4,
                        'max_tokens'  => 1200,
                    ],
                    'timeout' => 40,
                ]
            );
        } catch (\Throwable $e) {
            return [];
        }

        try {
            $data = $response->toArray(false);
            $raw  = $data['choices'][0]['message']['content'] ?? '{}';

            $raw  = $this->extractJson($raw);
            $json = json_decode($raw, true);

            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function extractJson(string $text): string
    {
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            return $m[0];
        }

        return $text;
    }
}