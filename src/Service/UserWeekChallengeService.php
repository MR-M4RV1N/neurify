<?php

namespace App\Service;

use App\Entity\Bookmark;
use App\Entity\BookmarkRegister;
use App\Entity\User;
use App\Entity\WeekChallenge;
use Doctrine\ORM\EntityManagerInterface;

class UserWeekChallengeService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Собирает пользователей по событию с флагом выполнения челенджа.
     *
     * @param int $eventId
     * @return array
     */
    public function getUsersWithChallengeStatus($eventId)
    {
        $bookmarkRepo = $this->entityManager->getRepository(Bookmark::class);
        $weekChallengeRepo = $this->entityManager->getRepository(WeekChallenge::class);
        $bookmarkRegisterRepo = $this->entityManager->getRepository(BookmarkRegister::class);

        // Получаем добавленных в закладки пользователей
        $addedToBookmark = $bookmarkRepo->findBy(['event' => $eventId]);

        // Получаем WeekChallenge для события
        $weekChallenge = $weekChallengeRepo->findOneBy(['event' => $eventId]);

        // Если WeekChallenge нет — просто возвращаем пользователей из закладок с completed = false
        if (!$weekChallenge) {
            return $this->mapBookmarksToUsers($addedToBookmark);
        }

        // Получаем пользователей, которые выполнили челендж
        $completedWeekChallenges = $bookmarkRegisterRepo->findBy(['week_challenge' => $weekChallenge]);

        // Собираем финальный список
        $users = [];

        // Добавляем выполненные
        foreach ($completedWeekChallenges as $bookmarkRegister) {
            $user = $bookmarkRegister->getUser();
            $users[$user->getId()] = [
                'user' => $user,
                'completed' => true,
            ];
        }

        // Добавляем тех, кто просто добавил в закладки
        foreach ($addedToBookmark as $bookmark) {
            $user = $bookmark->getUser();
            if (!isset($users[$user->getId()])) {
                $users[$user->getId()] = [
                    'user' => $user,
                    'completed' => false,
                ];
            }
        }

        return array_values($users);
    }

    /**
     * Преобразует закладки в массив пользователей с completed = false.
     *
     * @param Bookmark[] $bookmarks
     * @return array
     */
    private function mapBookmarksToUsers(array $bookmarks)
    {
        $users = [];

        foreach ($bookmarks as $bookmark) {
            $user = $bookmark->getUser();
            $users[$user->getId()] = [
                'user' => $user,
                'completed' => false,
            ];
        }

        return array_values($users);
    }
}