<?php

namespace App\Twig;

use App\Repository\LevelRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CheckUserLevelExtension extends AbstractExtension
{
    private $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('checkUserLevel', [$this, 'getLevel']),
        ];
    }

    public function getLevel($value, $lang)
    {
        // Если значение больше 100, то уровень равен 10
        if($value > 100){
            $levelNumber = 10;
            $level = $this->levelRepository->findOneBy(['number' => $levelNumber, 'lang' => $lang]);
        }
        elseif($value > 10){
            $levelNumber = ceil($value / 10);
            $level = $this->levelRepository->findOneBy(['number' => $levelNumber, 'lang' => $lang]);
        }
        else{
            $levelNumber = 1;
            $level = $this->levelRepository->findOneBy(['number' => $levelNumber, 'lang' => $lang]);
        }

        return $level->getNumber();
    }
}
