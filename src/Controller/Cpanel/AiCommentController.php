<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\AiComment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AiCommentController extends AbstractController
{
    /**
     * @Route("/events/with-ai-comments", name="event_with_ai_comments", methods={"GET"})
     */
    public function listWithComments(): Response
    {
        $comments = $this->getDoctrine()
            ->getRepository(\App\Entity\AiComment::class)
            ->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('cpanel/events/event/with_ai_comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/cpanel/editor/event/{id}/ai-comment", requirements={"id"="\d+"}, name="event_ai_comment", methods={"GET"})
     */
    public function aiComment(Event $event): Response
    {
        $comment = $this->getDoctrine()
            ->getRepository(AiComment::class)
            ->findOneBy(['event' => $event]);

        // Надо посчитать сколько осталось до следующего уровня. Берём все испытания (events) и делим на 10 - min(floor($allEvents / 10), 20),
        $user = $this->getUser();
        $eventRepo = $this->getDoctrine()->getRepository(Event::class);
        $allEvents = $eventRepo->count(['user' => $user]);
        $level = min(floor($allEvents / 10), 20);
        $nextLevel = ($level + 1) * 10;
        $remaining = $nextLevel - $allEvents;

        return $this->render('cpanel/events/event/ai_comment.html.twig', [
            'event' => $event,
            'comment' => $comment,
            'allEvents' => $allEvents,
            'level' => $level,
            'remaining' => $remaining,
        ]);
    }
}