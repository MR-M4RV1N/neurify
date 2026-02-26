<?php

namespace App\Controller\Cpanel;

use App\Service\NeurifyAiChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class NeurifyChatController extends AbstractController
{
    private $aiService;

    public function __construct(NeurifyAiChatService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * @Route("/cpanel/chat/neurify", name="neurify_chat")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $history = $session->get('neurify_chat_history', []);

        return $this->render('cpanel/chat/neurify_chat.html.twig', [
            'history' => $history,
            'currentMode' => $session->get('ai_mode', 'lite'),
        ]);
    }

    /**
     * @Route("/cpanel/chat/neurify/send", name="neurify_chat_send", methods={"POST"})
     */
    public function send(Request $request): Response
    {
        $session = $request->getSession();

        $message = trim($request->request->get('message', ''));
        if ($message === '') {
            return new Response('', 204);
        }

        // Чекбокс: если стоит — режим pro
        $mode = $request->request->get('mode') === 'pro' ? 'pro' : 'lite';
        $session->set('ai_mode', $mode);

        $history = $session->get('neurify_chat_history', []);

        // Ответ ИИ
        $reply = $this->aiService->sendMessage($message, $mode, $history, $this->getUser());

        $history[] = ['role' => 'user', 'content' => $message];
        if ($reply) {
            $history[] = ['role' => 'assistant', 'content' => $reply];
        }

        $session->set('neurify_chat_history', $history);

        return $this->render('cpanel/chat/_stream_append.html.twig', [
            'userMessage' => $message,
            'aiMessage'   => $reply,
        ], new Response('', 200, [
            'Content-Type' => 'text/vnd.turbo-stream.html',
        ]));
    }

    /**
     * @Route("/cpanel/chat/neurify/mode/{mode}", name="neurify_chat_set_mode")
     */
    public function setMode(string $mode, Request $request): Response
    {
        $allowed = ['lite', 'pro'];

        if (!in_array($mode, $allowed, true)) {
            $mode = 'lite';
        }

        $request->getSession()->set('ai_mode', $mode);

        return $this->redirectToRoute('neurify_chat');
    }

    /**
     * @Route("/cpanel/chat/neurify/starter/{key}", name="neurify_chat_starter", methods={"GET"})
     */
    public function starter(
        string $key,
        SessionInterface $session,
        NeurifyAiChatService $ai
    ): Response {
        $user = $this->getUser();

        $map = [
            'philosophy' => 'Расскажи про философию проекта',
            'what_to_do' => 'Что мне делать дальше?',
            'recommend_challenge' => 'Подскажи подходящее испытание',
        ];

        if (!isset($map[$key])) {
            return $this->redirectToRoute('neurify_chat');
        }

        $message = $map[$key];
        $mode = $session->get('ai_mode', 'lite');

        // Начинаем новую историю
        $history = [];

        $reply = $ai->sendMessage($message, $mode, $history, $user);

        $history[] = ['role' => 'user', 'content' => $message];
        $history[] = ['role' => 'assistant', 'content' => $reply];

        $session->set('neurify_chat_history', $history);

        return $this->redirectToRoute('neurify_chat');
    }

    /**
     * @Route("/cpanel/chat/clear", name="neurify_chat_clear", methods={"POST"})
     */
    public function clearChat(Request $request, SessionInterface $session): Response
    {
        if (!$this->isCsrfTokenValid('clear_chat', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        // Очистка истории чата
        $session->remove('neurify_chat_history');

        return $this->redirectToRoute('neurify_chat');
    }
}