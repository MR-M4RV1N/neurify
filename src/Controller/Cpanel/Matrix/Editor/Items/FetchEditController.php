<?php

namespace App\Controller\Cpanel\Matrix\Editor\Items;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixItem;
use App\Repository\MapRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FetchEditController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/edit/item/{id}", name="item.edit")
     */
    public function editItem(ManagerRegistry $doctrine, int $id): Response
    {
        return $this->render('cpanel/matrix/editor/item/edit.html.twig', [
            'item' => $doctrine->getRepository(MatrixItem::class)->find($id),
        ]);
    }
}
