<?php

namespace App\Controller\Client\Blog;

use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Repository\MapRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FetchShowController extends AbstractController
{
    /**
     * @Route("/post/{id}", name="devblog.show")
     */
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $arr = ArrayFromItemsService::getArray($doctrine->getRepository(MatrixItem::class)->findBy(['map' => $id]));

        return $this->render('devblog/show.html.twig', [
            'map' => $doctrine->getRepository(MatrixMap::class)->find($id),
            'items' => $arr,
        ]);
    }
}
