<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\NotificationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Event;
use Symfony\Component\Security\Core\Security;

class NotificationService
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Создать уведомление
     *
     * @param User $recipient Кому отправить уведомление
     * @param User $sender Кто инициировал действие
     * @param Event|null $event Событие, связанное с уведомлением
     * @param string $type Тип уведомления ('like', 'comment', 'bookmark', и т.д.)
     */
    public function createNotification(User $recipient, User $sender, ?Event $event, string $type): void
    {
        // Найти тип уведомления
        $notificationType = $this->em->getRepository(NotificationType::class)->findOneBy(['name' => $type]);

        if (!$notificationType) {
            throw new \LogicException(sprintf('Notification type "%s" not found.', $type));
        }

        // Создать уведомление
        $notification = new Notification();
        $notification->setRecipient($recipient);
        $notification->setSender($sender);
        $notification->setEvent($event);
        $notification->setType($notificationType);
        $notification->setIsRead(false);

        // Сохранить уведомление
        $this->em->persist($notification);
        $this->em->flush();
    }

    /**
     * Получить количество непрочитанных уведомлений
     */
    public function getUnreadCount(): int
    {
        $user = $this->security->getUser();
        if (!$user) {
            return 0;
        }

        return $this->em->getRepository(Notification::class)->count([
            'recipient' => $user,
            'isRead' => false,
        ]);
    }
}
