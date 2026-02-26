<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageOptimizerService
{
    public function resizeImage(UploadedFile $file, int $width, int $height): void
    {
        // Создать объект Imagick
        $imagick = new \Imagick($file->getPathname());

        // Установить формат изображения
        $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
        // Установить качество сжатия
        $imagick->setImageCompressionQuality(75);
        // Изменить размер изображения
        $imagick->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);

        // Записать изменения обратно в файл
        $imagick->writeImage($file->getPathname());

        // Очистить память
        $imagick->clear(); // очищает данные текущего изображения, но оставляет объект Imagick активным
        $imagick->destroy(); // полностью уничтожает объект Imagick и освобождает все связанные с ним ресурсы
    }
}
