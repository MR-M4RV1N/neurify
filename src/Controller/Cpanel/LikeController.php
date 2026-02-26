<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Service\NotificationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private $doctrine;
    private $likeRepository;

    public function __construct(ManagerRegistry $doctrine, LikeRepository $likeRepository)
    {
        $this->doctrine = $doctrine;
        $this->likeRepository = $likeRepository;
    }

    /**
     * @Route("/cpanel/like/check/{id}", name="app_like_check", methods={"GET"})
     */
    public function check($id): JsonResponse
    {
        if (!$this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $event = $this->doctrine->getRepository(Event::class)->find($id);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], Response::HTTP_NOT_FOUND);
        }

        $result = $this->likeRepository->isLikedByUser('event', $event->getId(), $this->getUser());

        return $this->json(['result' => $result]);
    }

    /**
     * @Route("/cpanel/like/count/{id}", name="app_like_count", methods={"GET"})
     */
    public function count($id): JsonResponse
    {
        if (!$this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $event = $this->doctrine->getRepository(Event::class)->find($id);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], Response::HTTP_NOT_FOUND);
        }

        $count = $this->likeRepository->countLikesFor('event', $event->getId());

        return $this->json(['result' => $count]);
    }

    /**
     * @Route("/cpanel/like/{id}", name="app_like", methods={"POST"})
     */
    public function like($id, NotificationService $notificationService): JsonResponse
    {
        if (!$this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $event = $this->doctrine->getRepository(Event::class)->find($id);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();
        $em = $this->doctrine->getManager();

        // Поиск лайка по targetType и targetId
        $like = $this->likeRepository->findOneBy([
            'user' => $user,
            'targetType' => 'event',
            'targetId' => $event->getId(),
        ]);

        if ($like) {
            $em->remove($like);
            $em->flush();
            $result = false;
        } else {
            $like = new Like();
            $like->setUser($user);
            $like->setTargetType('event');
            $like->setTargetId($event->getId());

            $em->persist($like);
            $em->flush();

            // Создаём уведомление о лайке
            $notificationService->createNotification(
                $event->getUser(),
                $user,
                $event,
                'like'
            );

            $result = true;
        }

        return $this->json(['result' => $result]);
    }
}