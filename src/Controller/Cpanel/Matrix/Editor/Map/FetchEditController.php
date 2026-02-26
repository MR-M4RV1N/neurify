<?php

namespace App\Controller\Cpanel\Matrix\Editor\Map;

use App\Entity\Category;
use App\Entity\Ensemble;
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

class FetchEditController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/edit/map/{id}", name="map.edit")
     */
    public function editMap(ManagerRegistry $doctrine, int $id): Response
    {
        $arr = ArrayFromItemsService::getArray($doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $id]));
        $map = $doctrine->getRepository(MatrixMap::class)->find($id);

        return $this->render('cpanel/matrix/editor/map/edit.html.twig', [
            'map' => $map,
            'isEmpty' => $doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $map]) ? 1 : 0,
            'ensembles' => $doctrine->getRepository(Ensemble::class)->findBy(['author' => $this->getUser()]),
            'items' => $arr,
        ]);
    }
}
