<?php

namespace App\Controller\Admin;

use App\Entity\Draft;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/cpanel/admin", name="cpanel_admin")
     */
    public function index(): Response
    {
        // Узнать сколько записей в таблице User
        $em = $this->getDoctrine()->getManager();
        $count = $em->getRepository(User::class)->count([]);
        // Выбрать последние 5 записей
        $lastUsers = $em->getRepository(User::class)->findBy([], ['id' => 'DESC'], 5);

        // Для каждого пользователя подсчитать количество записей в Draft и Event
        $userData = [];
        foreach ($lastUsers as $user) {
            $draftCount = $em->getRepository(Draft::class)->count(['user' => $user]);
            $eventCount = $em->getRepository(Event::class)->count(['user' => $user]);
            $userData[] = [
                'user' => $user,
                'draftCount' => $draftCount,
                'eventCount' => $eventCount,
            ];
        }

        return $this->render('cpanel/admin/index.html.twig', [
            'countUsers' => $count,
            'userData' => array_reverse($userData),
        ]);
    }
}
