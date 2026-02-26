<?php

namespace App\Controller\Cpanel\Matrix\Editor;

use App\Entity\Ensemble;
use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixMap;
use App\Repository\MatrixMapRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FetchListController extends AbstractController
{
    /**
     * @Route("/cpanel/editor/{ensemble}", name="editor.list", defaults={"ensemble"=0})
     */
    public function list(ManagerRegistry $doctrine, Request $request, $ensemble): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $doctrine->getRepository(MatrixMap::class)->findUserWithPaginator($offset, $this->getUser()->getId());
        $pgConf = new PaginatorConfig($offset, $paginator);

        return $this->render('cpanel/matrix/editor/list.html.twig', [
            'category' => $ensemble,
            'ensembles' => $doctrine->getRepository(Ensemble::class)->findBy(['author' => $this->getUser()]),
            'maps' => $paginator,
            'mapsLength' => ceil($paginator->count() / MatrixMapRepository::PAGINATOR_PER_PAGE),
            'previous' => $offset - MatrixMapRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + MatrixMapRepository::PAGINATOR_PER_PAGE),
            'before' => $pgConf->getBefore(),
            'after' => $pgConf->getAfter(),
            'per_page' => MatrixMapRepository::PAGINATOR_PER_PAGE,
        ]);
    }
}
