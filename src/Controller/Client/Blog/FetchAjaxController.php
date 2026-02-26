<?php

namespace App\Controller\Client\Blog;

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

class FetchAjaxController extends AbstractController
{
    /**
     * @Route("/client/blog/ajax/get/title", methods={"GET","POST"})
     */
    public function ajaxGetTitle(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');
        if($id)
        {
            $data = $doctrine->getRepository(MatrixItem::class)->find($id)->getTitle();
        }
        else
        {
            $data = 'Пусто';
        }

        return $this->json($data);
    }

    /**
     * @Route("/client/blog/ajax/get/description", methods={"GET","POST"})
     */
    public function ajaxGetDescription(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');
        if($id)
        {
            $data = $doctrine->getRepository(MatrixItem::class)->find($id)->getDescription();
        }
        else
        {
            $data = 'Пусто';
        }

        return $this->json($data);
    }

    /**
     * @Route("/client/blog/ajax/get/item", methods={"GET","POST"})
     */
    public function ajaxGetItem(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');
        if($id)
        {
            $data = $doctrine->getRepository(MatrixItem::class)->find($id)->getDescription();
        }
        else
        {
            $data = 'Пусто';
        }

        return $this->json($data);
    }

    /**
     * @Route("/client/blog/ajax/get/image", methods={"GET","POST"})
     */
    public function ajaxGetImage(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');
        if($id)
        {
            $data = $doctrine->getRepository(MatrixItem::class)->find($id)->getFilename();
        }
        else
        {
            $data = null;
        }

        return $this->json($data);
    }
}
