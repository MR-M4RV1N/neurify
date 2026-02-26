<?php

namespace App\Controller\Cpanel;

use App\Entity\Bookmark;
use App\Entity\BookmarkRegister;
use App\Entity\Event;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkController extends AbstractController
{
    /**
     * @Route("/cpanel/bookmark/add/{id}", name="bookmark_add", methods={"POST"})
     */
    public function add(Event $event, EntityManagerInterface $entityManager, NotificationService $notificationService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        // Создаём запись в Bookmark
        $bookmark = new Bookmark();
        $bookmark->setUser($user);
        $bookmark->setEvent($event);
        $entityManager->persist($bookmark);
        $entityManager->flush();

        // Создаём уведомление
        $notificationService->createNotification(
            $event->getUser(),       // Владелец события
            $this->getUser(),        // Текущий пользователь (поставивший лайк)
            $event,                  // Событие
            'bookmark'          // Тип уведомления
        );

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/cpanel/bookmark/remove/{id}", name="bookmark_remove", methods={"DELETE"})
     */
    public function remove(Event $event, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $bookmark = $entityManager->getRepository(Bookmark::class)->findOneBy([
            'user' => $user,
            'event' => $event,
        ]);

        if ($bookmark) {
            $entityManager->remove($bookmark);
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/cpanel/bookmark/remove-from-account/{id}", name="bookmark_remove_from_account", methods={"POST"})
     */
    public function removeFromAccount(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $bookmark = $entityManager->getRepository(Bookmark::class)->findOneBy([
            'user' => $user,
            'event' => $event,
        ]);

        if ($bookmark) {
            $entityManager->remove($bookmark);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bookmark_list');
    }
}
