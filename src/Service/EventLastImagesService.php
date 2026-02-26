<?php

namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;

class EventLastImagesService
{
    private $eventRepository;
    private $imageRepository;

    public function __construct(EventRepository $eventRepository, ImageRepository $imageRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Получить события с привязанными картинками.
     *
     * @param User $user Пользователь.
     * @param int $limit Лимит событий.
     * @return array
     */
    public function getEventsWithImages($user, int $limit = 5): array
    {
        // Получаем последние события
        $events = $this->eventRepository->findLastEvents($user, $limit);

        // Извлекаем ID событий
        $eventIds = array_map(function ($event) {
            return $event->getId();
        }, $events);

        // Получаем последние картинки для этих событий
        $images = $this->imageRepository->findLastImagesForEvents($eventIds);

        // Привязываем картинки к событиям
        foreach ($events as $event) {
            $eventImages = array_filter($images, function ($image) use ($event) {
                return $image->getEvent()->getId() === $event->getId();
            });

            // Добавляем изображения в коллекцию событий
            foreach ($eventImages as $image) {
                $event->getImages();
            }
        }

        return $events;
    }
}
