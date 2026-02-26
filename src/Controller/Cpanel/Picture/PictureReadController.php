<?php

namespace App\Controller\Cpanel\Picture;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureReadController extends AbstractController
{
    /**
     * @Route("/cpanel/picture/list", name="app_picture_list")
     */
    public function list(PictureRepository $pictureRepository): Response
    {
        // Получаем все записи Picture
        $pictures = $pictureRepository->findAll();

        // Рендерим шаблон с данными
        return $this->render('cpanel/picture/list.html.twig', [
            'pictures' => $pictures
        ]);
    }

    /**
     * @Route("/cpanel/picture/{id}", name="app_picture_show")
     */
    public function show(Picture $picture): Response
    {
        // Рендерим шаблон для отображения конкретной записи
        return $this->render('cpanel/picture/show.html.twig', [
            'picture' => $picture
        ]);
    }
}