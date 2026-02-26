<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LoopIndexCheckExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('checkLoop', [$this, 'check']),
        ];
    }

    public function check($value)
    {
        if($value % 2)
        {
            return 'loop-index';
        }
    }
}
