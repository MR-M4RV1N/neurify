<?php

namespace App\Repository;

use App\Entity\NotificationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationType>
 */
class NotificationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationType::class);
    }

    /**
     * Найти тип уведомления по названию
     *
     * @param string $name
     * @return NotificationType|null
     */
    public function findByName(string $name): ?NotificationType
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * Получить все типы уведомлений
     *
     * @return NotificationType[]
     */
    public function findAllTypes(): array
    {
        return $this->findAll();
    }
}
