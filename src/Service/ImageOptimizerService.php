<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageOptimizerService
{
    public function resizeImage(UploadedFile $file, int $width, int $height): void
    {
        $imagick = new \Imagick($file->getPathname());

        // Установить качество сжатия JPEG
        $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(75);

        // Записать изменения обратно в файл
        $imagick->writeImage($file->getPathname());

        // Очистить память
        $imagick->clear();
    }
}
