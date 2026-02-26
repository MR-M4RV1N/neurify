<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IssetNullExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('checkNull', [$this, 'check']),
        ];
    }

    public function check($value)
    {
        if(in_array(null, $value))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
