<?php

namespace App\EventSubscriber;

use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\NotificationType;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class LikeSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist, // Событие вызывается после добавления сущности
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        error_log('LikeSubscriber: postPersist called');

        if ($entity instanceof Like) {
            error_log('Like entity persisted: ID = ' . $entity->getId());
        }

        if (!$entity instanceof Like) {
            return;
        }

        $event = $entity->getEvent();
        $recipient = $event->getUser();
        $sender = $entity->getUser();

        // Создание уведомления
        $notification = new Notification();
        $notification->setRecipient($recipient);
        $notification->setSender($sender);
        $notification->setEvent($event);

        // Получаем тип уведомления "лайк"
        $em = $args->getEntityManager();
        $notificationType = $em->getRepository(NotificationType::class)
            ->findOneBy(['name' => 'like']);
        if (!$notificationType) {
            throw new \LogicException('Notification type "like" not found in the database.');
        }
        $notification->setType($notificationType);

        $em->persist($notification);
        $em->flush();
    }
}