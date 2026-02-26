<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ActiveMenuExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('area', [$this, 'isActive']),
        ];
    }

    public function isActive($value, $link)
    {
        if(str_contains($value, $link))
        {
          return 'active';
		}
        else
        {
          return null;
		}
    }
}
