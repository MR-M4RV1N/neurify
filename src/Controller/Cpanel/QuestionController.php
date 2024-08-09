<?php

namespace App\Controller\Cpanel;

use App\Service\OpenAIService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $openAIService;
    private $logger;

    public function __construct(OpenAIService $openAIService, LoggerInterface $logger)
    {
        $this->openAIService = $openAIService;
        $this->logger = $logger;
    }

    /**
     * @Route("/cpanel/project/assistant/ask", name="ask_question", methods={"POST"})
     */
    public function ask(Request $request): JsonResponse
    {
        $this->logger->info('Received request in QuestionController');

        $data = json_decode($request->getContent(), true);
        $question = $data['question'] ?? '';

        $this->logger->info('Parsed request data', ['data' => $data]);

        if (empty($question)) {
            $this->logger->error('Question is empty');
            return $this->json(['error' => 'Question cannot be empty'], 400);
        }

        try {
            $this->logger->info('Sending question to OpenAIService', ['question' => $question]);
            $answer = $this->openAIService->getAnswer($question);
            $this->logger->info('Received answer from OpenAIService', ['answer' => $answer]);
            return $this->json(['answer' => $answer]);
        } catch (\Exception $e) {
            $this->logger->error('Error in QuestionController', ['exception' => $e]);
            return $this->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
