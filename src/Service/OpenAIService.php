<?php
// src/Service/OpenAIService.php
namespace App\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class OpenAIService
{
    private $client;
    private $apiKey;
    private $logger;

    public function __construct(string $apiKey, LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->apiKey = $apiKey;
        $this->logger = $logger;
    }

    public function getAnswer(string $question): string
    {
        try {
            $this->logger->info('Sending request to OpenAI API', ['question' => $question]);

            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo', // или 'gpt-4'
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $question]
                    ],
                    'max_tokens' => 100,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            $this->logger->info('Received response from OpenAI API', [
                'status_code' => $statusCode,
                'body' => $body,
            ]);

            if ($statusCode !== 200) {
                $this->logger->error('Failed to fetch response from OpenAI', ['status_code' => $statusCode]);
                throw new \Exception('Failed to fetch response from OpenAI');
            }

            $data = json_decode($body, true);

            if (!isset($data['choices'][0]['message']['content'])) {
                $this->logger->error('Invalid response structure', ['response' => $data]);
                throw new \Exception('Invalid response structure');
            }

            return $data['choices'][0]['message']['content'];
        } catch (\Exception $e) {
            $this->logger->error('Error in OpenAIService', ['exception' => $e->getMessage()]);
            throw $e;
        }
    }
}
