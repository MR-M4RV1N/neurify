<?php

namespace App\Form\DataTransformer;

use HTMLPurifier;
use HTMLPurifier_Config;
use Symfony\Component\Form\DataTransformerInterface;

class HTMLPurifierTransformer implements DataTransformerInterface
{
    private $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
    }

    public function transform($value)
    {
        return $value ?? ''; // Возвращаем данные как есть (отображение)
    }

    public function reverseTransform($value)
    {
        return $this->purifier->purify($value); // Очистка данных
    }
}