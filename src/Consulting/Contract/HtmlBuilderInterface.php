<?php

namespace App\Consulting\Contract;

interface HtmlBuilderInterface
{
    /**
     * @param array $data нормализованные данные
     */
    public function build(array $data): string;
}
