<?php

namespace App\Controller\Cpanel;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // правильный импорт для Symfony 5.4

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/cpanel/change-password", name="app_change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы.');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();

            if ($newPassword) {
                // Хешируем новый пароль
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                $em->flush();

                $this->addFlash('success', 'Пароль успешно изменён.');

                return $this->redirectToRoute('app_change_password');
            } else {
                $this->addFlash('danger', 'Новый пароль не может быть пустым.');
            }
        }

        return $this->render('cpanel/profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}