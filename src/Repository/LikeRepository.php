<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function add(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countLikesFor(string $type, int $targetId): int
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.targetType = :type')
            ->andWhere('l.targetId = :id')
            ->setParameters([
                'type' => $type,
                'id' => $targetId,
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function isLikedByUser(string $type, int $targetId, User $user): bool
    {
        return (bool) $this->findOneBy([
            'user' => $user,
            'targetType' => $type,
            'targetId' => $targetId,
        ]);
    }

    public function findLikedIdsByUserAndType(User $user, string $type): array
    {
        return array_map(
            function ($like) {
                return $like['targetId'];
            },
            $this->createQueryBuilder('l')
                ->select('l.targetId')
                ->where('l.user = :user')
                ->andWhere('l.targetType = :type')
                ->setParameters([
                    'user' => $user,
                    'type' => $type,
                ])
                ->getQuery()
                ->getArrayResult()
        );
    }

    // Дополнительно: для подсчёта лайков по событиям пользователя
    public function countLikesForUserEvents(User $user, array $userEventIds): int
    {
        if (empty($userEventIds)) {
            return 0;
        }

        return $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.targetType = :type')
            ->andWhere('l.targetId IN (:ids)')
            ->setParameter('type', 'event')
            ->setParameter('ids', $userEventIds)
            ->getQuery()
            ->getSingleScalarResult();
    }
}