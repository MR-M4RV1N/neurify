<?php

namespace App\Service;

use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\QueryBuilder;

class PaginatorService
{
    public function paginate(QueryBuilder $queryBuilder, int $currentPage, int $maxPerPage): Pagerfanta
    {
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }
}
