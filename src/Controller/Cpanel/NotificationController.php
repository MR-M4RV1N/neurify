<?php

namespace App\Controller\Cpanel;

use App\Entity\Chat;
use App\Entity\Messages;
use App\Entity\Notification;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\MessagesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cpanel/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/list", name="cpanel_notification_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        // Получаем текущего пользователя
        $user = $this->getUser();
        // Извлекаем уведомления для текущего пользователя
        $notifications = $doctrine->getRepository(Notification::class)->findBy(
            ['recipient' => $user], // Только уведомления для текущего пользователя
            ['createdAt' => 'DESC'], // Сортируем от новых к старым
            20
        );
        // Пометить все уведомления как прочитанные
        $entityManager = $doctrine->getManager();
        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
            $entityManager->persist($notification);
        }
        $entityManager->flush();

        return $this->render('cpanel/notification/list.html.twig', [
            'notifications' => $notifications
        ]);
    }
}
