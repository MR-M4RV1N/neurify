<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ImageRemovalService
{
    public function removeImage(string $imageName, string $uploadDirectory): void
    {
        if (strpos($imageName, 'example-') === 0) {
            return;
        }

        if ($imageName !== 'default.jpg') {
            $filesystem = new Filesystem();
            $imagePath = $uploadDirectory.'/'.$imageName;
            try {
                if ($filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }
            } catch (IOExceptionInterface $exception) {
                // Логируйте ошибку или обрабатывайте исключение, если файл не удалось удалить
                // Например: echo "An error occurred while deleting your file at ".$exception->getPath();
            }
        }
    }
}