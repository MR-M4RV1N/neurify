<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserProgressUpdater
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Обновляет прогресс пользователя.
     *
     * @param User $user Пользователь, чьи данные нужно обновить.
     * @return void
     */
    public function updateUserProgress(User $user): void
    {
        // Увеличиваем значение прогресса
        $user->setProgress($user->getProgress() + 1);

        // Сохраняем изменения
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
