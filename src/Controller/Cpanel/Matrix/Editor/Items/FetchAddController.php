<?php

namespace App\Controller\Cpanel\Matrix\Editor\Items;

use App\Entity\Item;
use App\Entity\Map;
use App\Repository\MapRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class   FetchAddController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/add/item/{id}", name="item.add")
     */
    public function addItem(ManagerRegistry $doctrine, int $id): Response
    {
        return $this->render('cpanel/matrix/editor/item/add.html.twig', [
            'mapId' => $id,
        ]);
    }
}
