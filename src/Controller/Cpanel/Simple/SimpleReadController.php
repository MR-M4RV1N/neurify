<?php

namespace App\Controller\Cpanel\Simple;

use App\Entity\Simple;
use App\Repository\SimpleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleReadController extends AbstractController
{
    /**
     * @Route("/cpanel/simple/list", name="app_simple_list")
     */
    public function list(SimpleRepository $simpleRepository): Response
    {
        // Получаем все записи Simple
        $simples = $simpleRepository->findAll();

        // Рендерим шаблон с данными
        return $this->render('cpanel/simple/list.html.twig', [
            'simples' => $simples
        ]);
    }

    /**
     * @Route("/cpanel/simple/{id}", name="app_simple_show")
     */
    public function show(Simple $simple): Response
    {
        // Рендерим шаблон для отображения конкретной записи
        return $this->render('cpanel/simple/show.html.twig', [
            'simple' => $simple
        ]);
    }
}