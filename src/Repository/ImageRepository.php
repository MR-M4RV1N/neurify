<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Найти по одной картинке для последних 5 событий.
     *
     * @param array $eventIds ID последних событий.
     * @return array
     */
    public function findLastImagesForEvents(array $eventIds): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.event IN (:eventIds)')
            ->setParameter('eventIds', $eventIds)
            ->orderBy('i.id', 'DESC') // Убедитесь, что загружаем последние картинки
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти изображения для последних событий.
     * Возвращает по одному изображению для каждого события.
     *
     * @param array $eventIds ID событий.
     * @return array
     */
    public function findImagesForEvents(array $eventIds): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.event IN (:eventIds)')
            ->setParameter('eventIds', $eventIds)
            ->groupBy('i.event') // Группируем по событию
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти все изображения для события.
     *
     * @param int $eventId ID события.
     * @return array
     */
    public function findAllImagesForEvent(int $eventId): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getResult();
    }
}