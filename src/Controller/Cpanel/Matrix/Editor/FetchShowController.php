<?php

namespace App\Controller\Cpanel\Matrix\Editor;

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
     * @Route("/cpanel/editor/show/{id}", name="editor.show")
     */
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $arr = ArrayFromItemsService::getArray($doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $id]));
        $map = $doctrine->getRepository(MatrixMap::class)->find($id);

        return $this->render('cpanel/matrix/editor/show.html.twig', [
            'map' => $map,
            'isEmpty' => $doctrine->getRepository(MatrixItem::class)->findBy(['matrix_map' => $map]) ? 1 : 0,
            'items' => $arr,
        ]);
    }
}
