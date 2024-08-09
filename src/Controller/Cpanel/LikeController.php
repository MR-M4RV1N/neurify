<?php

namespace App\Controller\Cpanel;

use App\Entity\Event;
use App\Entity\Like;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/cpanel/like/check/{id}", name="app_like_check")
     */
    public function check($id): Response
    {
        // Проверка авторизован ли пользователь
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Найти Event по id
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        // Проверка существует ли в Like запись, где user = текущий пользователь и event = переданный id. Если существует, то возвращаем $result = true, иначе false
        $result = $this->getDoctrine()->getRepository(Like::class)->findOneBy(['user' => $this->getUser(), 'event' => $event]) ? true : false;

        return $this->json(['result' => $result]);
    }

    /**
     * @Route("/cpanel/like/count/{id}", name="app_like_count")
     */
    public function count($id): Response
    {
        // Проверка авторизован ли пользователь
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Найти Event по id
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        // Подсчитать количество записей в Like, где event = переданный id
        $result = $this->getDoctrine()->getRepository(Like::class)->count(['event' => $event]);

        return $this->json(['result' => $result]);
    }

    /**
     * @Route("/cpanel/like/{id}", name="app_like")
     */
    public function like($id): Response
    {
        // Проверка авторизован ли пользователь
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Найти Event по id
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        // Проверка существует ли в Like запись, где user = текущий пользователь и event = переданный id
        $like = $this->getDoctrine()->getRepository(Like::class)->findOneBy(['user' => $this->getUser(), 'event' => $event]);
        // Если запись существует, то удаляем ее, иначе создаем новую запись
        if ($like) {
            $this->getDoctrine()->getManager()->remove($like);
            $result = false;
        } else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setEvent($event);
            $this->getDoctrine()->getManager()->persist($like);
            $result = true;
        }

        return $this->json(['result' => $result]);
    }
}
