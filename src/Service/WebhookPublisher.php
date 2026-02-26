<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ZapierLinkRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebhookPublisher
{
    private $httpClient;
    private $defaultWebhookUrl;
    private $zapierLinkRepository;

    public function __construct(
        HttpClientInterface $httpClient,
        ZapierLinkRepository $zapierLinkRepository,
        string $defaultWebhookUrl
    ) {
        $this->httpClient = $httpClient;
        $this->zapierLinkRepository = $zapierLinkRepository;
        $this->defaultWebhookUrl = $defaultWebhookUrl;
    }

    /**
     * Отправляет данные на вебхук, используя ссылку пользователя, если она есть.
     */
    public function sendForUser(User $user, array $data): void
    {
        $zapierLink = $this->zapierLinkRepository->findOneBy(['user' => $user]);
        $url = $zapierLink ? $zapierLink->getUrl() : $this->defaultWebhookUrl;

        $this->httpClient->request('POST', $url, [
            'json' => $data,
        ]);
    }
}