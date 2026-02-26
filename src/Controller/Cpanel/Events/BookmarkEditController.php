<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\Bookmark;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\BookmarkRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BookmarkEditController extends AbstractController
{
    /**
     * @Route("/cpanel/bookmark/delete/{id}", requirements={"id"="\d+"}, name="app_bookmark_delete", methods={"POST"})
     */
    public function delete(ManagerRegistry $doctrine, Security $security, Request $request, BookmarkRepository $bookmarkRepository, Event $event): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для выполнения этого действия.');
        }

        $entityManager = $doctrine->getManager();
        $bookmark = $bookmarkRepository->findOneBy(['event' => $event, 'user' => $user]);
        if (!$bookmark) {
            throw $this->createNotFoundException('Закладка не найдена.');
        }
        // Проверяем, что закладка принадлежит текущему пользователю
        if ($bookmark->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Вы не можете удалить эту закладку.');
        }
        // Удаление закладки
        $entityManager->remove($bookmark);
        $entityManager->flush();

        return $this->redirectToRoute('app_bookmark_list', [], Response::HTTP_SEE_OTHER);
    }

}
