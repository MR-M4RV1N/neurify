<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SanskritLetterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('checkSanskritLetter', [$this, 'getLetter']),
        ];
    }

    public function getLetter($value)
    {
        switch ($value) {
            case 1:
                return 'א';
            case 2:
                return 'ב';
            case 3:
                return 'ג';
            case 4:
                return 'ד';
            case 5:
                return 'ה';
            case 6:
                return 'ו';
            case 7:
                return 'ז';
            case 8:
                return 'ח';
            case 9:
                return 'ט';
        }
    }
}
