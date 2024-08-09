<?php

namespace App\Service;

class ArrayFromItemsService
{
    static function getArray($value): array
    {
        $arr = [];
        foreach($value as $item) {
            $ar = [];
            array_push($ar, $item->getId());
            array_push($ar, $item->getTitle());
            array_push($arr, $ar);
        }
        $c = 9 - count($arr);
        for($i = 1; $i <= $c; $i++)
        {
            array_push($arr, null);
        }

        return $arr;
    }
}