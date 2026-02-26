<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetCorrectUsernameExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('correctUsername', [$this, 'getUsername']),
        ];
    }

    public function getUsername($value)
    {
        // Если в поле username есть пробелы, то
        if (strpos($value, ' ') !== false) {
            // Удаляем пробелы
            $username = str_replace(' ', '-', $value);
            return $username;
        }
        else{
            return $value;
        }
    }
}
