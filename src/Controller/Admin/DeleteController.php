<?php


namespace App\Controller\Admin;

use App\Entity\Draft;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DeleteController extends AbstractController
{
    private $em;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $em, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->em = $em;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @Route("/cpanel/admin/delete", name="cpanel_admin_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        // Проверка CSRF токена
        $csrfToken = new CsrfToken('delete_user', $request->request->get('_csrf_token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw $this->createAccessDeniedException('CSRF token invalid.');
        }

        // Получаем id пользователя
        $id = $request->request->get('userId');

        // Находим пользователя по id
        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            // Обработка ошибки, если пользователь не найден
            throw $this->createNotFoundException('Пользователь с id ' . $id . ' не найден.');
        }

        // В Draft найти все записи с user_id = id
        $drafts = $this->em->getRepository(Draft::class)->findBy(['user' => $user]);
        if (!$drafts) {
            // Обработка ошибки, если черновики не найдены
            throw $this->createNotFoundException('Черновики для пользователя с id ' . $id . ' не найдены.');
        }

        // Удалить все записи из Draft
        foreach ($drafts as $draft) {
            $this->em->remove($draft);
        }

        // Удаляем пользователя
        $this->em->remove($user);

        // Сохраняем изменения
        $this->em->flush();

        // Перенаправляем на главную страницу админки
        return $this->redirectToRoute('cpanel_admin');
    }
}
