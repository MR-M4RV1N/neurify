<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Event;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * Получает директорию загрузки для изображений коллекций.
     */
    public function getUploadDirectory(): string
    {
        return $this->params->get('image_upload_directory_collections');
    }

    /**
     * Загружает изображения и добавляет их к событию.
     *
     * @param array $uploadedImages Список загруженных файлов
     * @param Event $event          Сущность Event, к которой привязываются изображения
     *
     * @return void
     */
    public function uploadImages(array $uploadedImages, Event $event): void
    {
        $uploadDirectory = $this->getUploadDirectory();

        foreach ($uploadedImages as $uploadedImage) {
            if (!$uploadedImage instanceof UploadedFile) {
                continue;
            }

            // Генерация уникального имени файла
            $fileName = uniqid() . '.' . $uploadedImage->guessExtension();

            // Перемещение файла в директорию загрузок
            $uploadedImage->move($uploadDirectory, $fileName);

            // Создание сущности Image
            $image = new Image();
            $image->setUrl($fileName);

            // Добавление изображения к событию
            $event->addImage($image);
        }
    }
}