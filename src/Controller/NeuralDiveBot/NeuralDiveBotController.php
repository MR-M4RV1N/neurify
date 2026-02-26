<?php

namespace App\Controller\NeuralDiveBot;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\NeuralDiveBot\NeuralDiveBotUser;

class NeuralDiveBotController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/user/{userId}/progress", name="update_user_progress", methods={"PATCH"})
     */
    public function updateNeuralDiveBotUser(int $userId, Request $request): JsonResponse
    {
        $repository = $this->entityManager->getRepository(NeuralDiveBotUser::class);
        $neuralDiveBotUser = $repository->find($userId);

        if (!$neuralDiveBotUser) {
            // Если пользователь не найден, создаем запись для него
            $neuralDiveBotUser = new NeuralDiveBotUser();
            $neuralDiveBotUser->setUserIdNumber($userId);
            $neuralDiveBotUser->setReadPostsCount(0);
            $this->entityManager->persist($neuralDiveBotUser);
        }

        $increment = $request->get('increment', 0);
        $neuralDiveBotUser->setReadPostsCount($neuralDiveBotUser->getReadPostsCount() + $increment);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Progress updated successfully']);
    }

    /**
     * @Route("/api/user/{userId}/progress", name="get_user_progress", methods={"GET"})
     */
    public function getNeuralDiveBotUser(int $userId): JsonResponse
    {
        $repository = $this->entityManager->getRepository(NeuralDiveBotUser::class);
        $neuralDiveBotUser = $repository->find($userId);

        if (!$neuralDiveBotUser) {
            // Если пользователь не найден, возвращаем 0 прочитанных постов
            return new JsonResponse([
                'user_id_number' => $userId,
                'read_posts_count' => 0,
                'level' => 1,
            ]);
        }

        $readPostsCount = $neuralDiveBotUser->getReadPostsCount();
        $level = intval($readPostsCount / 10) + 1; // Уровень рассчитывается на основе количества прочитанных постов

        return new JsonResponse([
            'user_id_number' => $userId,
            'read_posts_count' => $readPostsCount,
            'level' => $level,
        ]);
    }
}
