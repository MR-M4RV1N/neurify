<?php

namespace App\Controller\NeuralDiveBot;

use App\Entity\NeuralDiveBot\NeuralDivePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NeuralDivePostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/neural-dive-bot/posts", name="neural_dive_create_post", methods={"POST"})
     */
    public function createPost(Request $request): JsonResponse
    {
        $title = $request->get('title');
        $content = $request->get('content');

        if (empty($title) || empty($content)) {
            return new JsonResponse(['error' => 'Title and content are required.'], 400);
        }

        $post = new NeuralDivePost();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setCreatedAt(new \DateTime());
        $post->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Post created successfully', 'id' => $post->getId()], 201);
    }

    /**
     * @Route("/api/neural-dive-bot/posts/{id}", name="neural_dive_get_post", methods={"GET"})
     */
    public function getPost(int $id): JsonResponse
    {
        $post = $this->entityManager->getRepository(NeuralDivePost::class)->find($id);

        if (!$post) {
            return new JsonResponse(['error' => 'Post not found.'], 404);
        }

        return new JsonResponse([
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $post->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @Route("/api/neural-dive-bot/posts", name="neural_dive_get_all_posts", methods={"GET"})
     */
    public function getAllPosts(): JsonResponse
    {
        $posts = $this->entityManager->getRepository(NeuralDivePost::class)->findAll();
        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $post->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/neural-dive-bot/posts/{id}", name="neural_dive_update_post", methods={"PUT"})
     */
    public function updatePost(int $id, Request $request): JsonResponse
    {
        $post = $this->entityManager->getRepository(NeuralDivePost::class)->find($id);

        if (!$post) {
            return new JsonResponse(['error' => 'Post not found.'], 404);
        }

        $title = $request->get('title');
        $content = $request->get('content');

        if (!empty($title)) {
            $post->setTitle($title);
        }

        if (!empty($content)) {
            $post->setContent($content);
        }

        $post->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Post updated successfully']);
    }

    /**
     * @Route("/api/neural-dive-bot/posts/{id}", name="neural_dive_delete_post", methods={"DELETE"})
     */
    public function deletePost(int $id): JsonResponse
    {
        $post = $this->entityManager->getRepository(NeuralDivePost::class)->find($id);

        if (!$post) {
            return new JsonResponse(['error' => 'Post not found.'], 404);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Post deleted successfully']);
    }
}
