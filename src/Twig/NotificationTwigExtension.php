<?php

namespace App\Twig;

use App\Service\NotificationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NotificationTwigExtension extends AbstractExtension
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('unread_notifications_count', [$this, 'getUnreadNotificationsCount']),
        ];
    }

    public function getUnreadNotificationsCount(): int
    {
        return $this->notificationService->getUnreadCount();
    }
}
