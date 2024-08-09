<?php

namespace App\Controller\Cpanel;

use App\Entity\Comment;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/cpanel/comment/new/{id}", name="app_comment_new", methods={"POST"})
     */
    public function new(Event $event, Request $request, ManagerRegistry $doctrine): Response
    {
        // Проверяем CSRF-токен
        if (!$this->isCsrfTokenValid('new-comment', $request->request->get('csrf_token'))) {
            // Возвращаем ошибку, если токен не валиден
            $this->addFlash('error', 'Неверный CSRF-токен.');
            return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
        }
        // Проверяем, что событие публичное
        if (!$event->isPublic()) {
            // Возвращаем ошибку, если событие не публичное
            $this->addFlash('error', 'Вы не можете оставить комментарий к закрытому событию.');
            return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
        }
        // Получаем текст комментария из запроса
        $commentText = $request->request->get('text');
        // Предполагаемая валидация данных
        if (empty($commentText)) {
            // Возвращаем ошибку, если текст комментария пуст
            $this->addFlash('error', 'Текст комментария не может быть пустым.');
            return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
        }

        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setEvent($event);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setText($commentText);
        // Проверяем parent_id
        $parentId = $request->request->get('parent');
        // Если parent не пустой, то ищем комментарий с таким id
        if ($parentId) {
            $parentComment = $doctrine->getRepository(Comment::class)->find($parentId);
            // Если комментарий не найден, то возвращаем ошибку
            if (!$parentComment) {
                $this->addFlash('error', 'Родительский комментарий не найден.');
                return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
            }
            // Если комментарий найден, то устанавливаем его в качестве родительского
            $comment->setParent($parentComment);
        }

        try {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        } catch (\Exception $e) {
            // Логирование ошибки или обработка исключения
            $this->addFlash('error', 'Произошла ошибка при сохранении комментария.');
            return $this->redirectToRoute('app_public_show', ['id' => $event->getId()]);
        }

        // Возвращаемся на страницу события
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/cpanel/comment/delete/{id}", name="app_comment_delete", methods={"POST"})
    */
    public function delete(Comment $comment, ManagerRegistry $doctrine, Request $request): Response
    {
        // Проверяем CSRF-токен
        if (!$this->isCsrfTokenValid('delete-comment', $request->request->get('csrf_token'))) {
            // Возвращаем ошибку, если токен не валиден
            $this->addFlash('error', 'Неверный CSRF-токен.');
            return $this->redirectToRoute('app_public_show', ['id' => $comment->getEvent()->getId()]);
        }
        // Проверяем, что комментарий принадлежит текущему пользователю
        if ($comment->getUser() !== $this->getUser()) {
            // Возвращаем ошибку, если комментарий не принадлежит текущему пользователю
            $this->addFlash('error', 'Вы не можете удалить чужой комментарий.');
            return $this->redirectToRoute('app_public_show', ['id' => $comment->getEvent()->getId()]);
        }
        try {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        } catch (\Exception $e) {
            // Логирование ошибки или обработка исключения
            $this->addFlash('error', 'Произошла ошибка при удалении комментария.');
            return $this->redirectToRoute('app_public_show', ['id' => $comment->getEvent()->getId()]);
        }

        // Возвращаемся на страницу события
        return $this->redirect($request->headers->get('referer'));
    }
}
