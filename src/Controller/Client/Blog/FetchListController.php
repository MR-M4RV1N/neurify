<?php

namespace App\Controller\Client\Blog;

use App\Entity\Category;
use App\Entity\Ensemble;
use App\Entity\Item;
use App\Entity\Map;
use App\Entity\MatrixItem;
use App\Entity\MatrixMap;
use App\Repository\MapRepository;
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
     * @Route("/client/blog/category/{category}", name="blog.category")
     */
        public function listCategory(ManagerRegistry $doctrine, Request $request, $category): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        if($category == 'all'){
            $categoryName = 'Все посты';
            $categoryDescription = 'Посты на тему биохакинга и больших идей о которых нужно знать';
            $categoryKeywords = 'Биохакинг, Большие идеи';
            $paginator = $doctrine->getRepository(MatrixMap::class)->findWithPaginator($offset);
        }
        else{
            $paginator = $doctrine->getRepository(MatrixMap::class)->findCategoryWithPaginator($offset, $category);

            if($category == 0){
                $categoryName = 'Без категории';
                $categoryDescription = 'Посты на тему биохакинга и больших идей о которых нужно знать';
                $categoryKeywords = 'Биохакинг, Большие идеи';
            }
            else{
                $categoryName = $doctrine->getRepository(Ensemble::class)->find($category)->getName();
                $categoryDescription = $doctrine->getRepository(Ensemble::class)->find($category)->getDescription();
                $categoryKeywords = $doctrine->getRepository(Ensemble::class)->find($category)->getKeywords();
            }
        }

        $pgConf = new PaginatorConfig($offset, $paginator);

        return $this->render('client/blog/category.html.twig', [
            'categoryName' => $categoryName,
            'categoryDescription' => $categoryDescription,
            'categoryKeywords' => $categoryKeywords,
            'category' => $category,
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
