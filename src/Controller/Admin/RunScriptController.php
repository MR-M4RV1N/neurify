<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Repository\EventRepository;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RunScriptController extends AbstractController
{
    /**
     * @Route("/admin/run/script", methods={"GET"})
    */
    public function runScript(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        Security $security
    ): Response {
        // Проверяем, что пользователь — админ
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied');
        }

        // Создаём новый экземпляр пользователя (или бери своего реального)
        $user = new \App\Entity\User(); // Подставь свой неймспейс User-а, если другой

        // Твой новый пароль
        $plainPassword = 'Кот#НаСтоле2025!';

        // Шифруем пароль
        $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);

        // Выводим зашифрованный пароль
        return new Response('<pre>' . htmlspecialchars($encodedPassword) . '</pre>');
    }
}
