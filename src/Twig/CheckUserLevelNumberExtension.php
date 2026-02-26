<?php

namespace App\Twig;

use App\Repository\LevelRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CheckUserLevelNumberExtension extends AbstractExtension
{
    private $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('checkUserLevelNumber', [$this, 'getLevelNumber']),
        ];
    }

    public function getLevelNumber($value)
    {
        if($value > 100){
            $levelNumber = 10;
        }
        elseif($value > 10){
            $levelNumber = ceil($value / 10);
        }
        else{
            $levelNumber = 1;
        }

        return $levelNumber;
    }
}
