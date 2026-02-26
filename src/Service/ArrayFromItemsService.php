<?php

namespace App\Service;

class ArrayFromItemsService
{
    static function getArray($value): array
    {
        $arr = [];
        foreach ($value as $item) {
            $ar = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'description' => $item->getDescription(),
                'filename' => $item->getFilename()
            ];
            $arr[] = $ar;
        }

        $missing = 9 - count($arr);
        for ($i = 0; $i < $missing; $i++) {
            $arr[] = null;
        }

        return $arr;
    }
}