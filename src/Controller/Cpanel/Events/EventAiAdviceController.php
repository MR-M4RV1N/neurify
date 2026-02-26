<?php

namespace App\Controller\Cpanel\Events;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Event;
use App\Service\AiAdviceService;
use Symfony\Component\HttpFoundation\Request;

class EventAiAdviceController extends AbstractController
{
    /**
     * @Route("/cpanel/event/ai/advice", name="event_ai_advice", methods={"GET"})
     */
    public function advice(AiAdviceService $aiAdviceService): Response {
        // Получить послуднюю запись Event, которая была создана пользователем
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);
        $lastEvent = $eventRepository->findOneBy(['user' => $user], ['id' => 'DESC']);
        if (!$lastEvent) {
            $this->addFlash('error', 'No events found for the user.');
            return $this->redirectToRoute('app_account_statistics');
        }
        // Взять поле description и получить совет от AI по улучшению
        $description = $lastEvent->getDescription();
        if (!$description) {
            $this->addFlash('error', 'No description found for the last event.');
            return $this->redirectToRoute('app_account_statistics');
        }
        // Используем сервис для получения совета от AI
        $aiAdvice = $aiAdviceService->generateAdvice($lastEvent);

        return $this->render('cpanel/events/ai_advice.html.twig', [
            'lastEvent' => $lastEvent,
            'aiAdvice' => $aiAdvice,
        ]);
    }

    /**
     * @Route("/cpanel/event/ai/advice/send", name="event_ai_advice_send", methods={"POST"})
    */
    public function sendAdvice(Request $request, EntityManagerInterface $em, AiAdviceService $aiAdviceService): Response {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        $eventId = $request->request->get('eventId');
        $aiAdvice = $request->request->get('aiAdvice');
        if (!$eventId || !$aiAdvice) {
            $this->addFlash('error', 'Не хватает данных для обновления события.');
            return $this->redirectToRoute('app_account_statistics');
        }

        $eventRepo = $em->getRepository(Event::class);
        $event = $eventRepo->findOneBy(['id' => $eventId, 'user' => $user]);
        if (!$event) {
            $this->addFlash('error', 'Событие не найдено или не принадлежит вам.');
            return $this->redirectToRoute('app_account_statistics');
        }
        $event->setDescription($aiAdvice);
        $em->flush();

        $this->addFlash('success', 'Описание события обновлено на основе совета AI.');
        return $this->redirectToRoute('app_account_statistics');
    }
}