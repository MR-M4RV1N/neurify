<?php

namespace App\Service;

use App\Repository\MapRepository;
use App\Repository\MatrixMapRepository;

class PaginatorConfig
{
    private $total;
    private $current;
    private $indent;
    private $restriction;

    public function __construct(int $offset, object $paginator)
    {
        // Количество цифр в пагинаторе:
        $this->total = ceil($paginator->count() / MatrixMapRepository::PAGINATOR_PER_PAGE);
        // На какой цифре сейчас страница:
        $this->current = $offset / MatrixMapRepository::PAGINATOR_PER_PAGE;
        // Этот параметр можно менять (количество кнопок до и после):
        $this->indent = 4;
        // Это нужно чтобы ограничить выводимый диапозон:
        $this->restriction = $this->indent+1;
    }


    public function getBefore(): string
    {
        if($this->current < $this->restriction)
        {
            $before = 0;
        }
        else
        {
            $before = $this->current - $this->indent;
        }

        return $before;
    }

    public function getAfter(): string
    {
        if($this->current > $this->total - $this->restriction)
        {
            $after = $this->total-1;
        }
        else
        {
            $after = $this->current + $this->indent;
        }

        return $after;
    }
}