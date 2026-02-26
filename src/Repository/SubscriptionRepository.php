<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * Найти подписки для конкретного пользователя (подписки, которые пользователь оформил)
     */
    public function findSubscriptionsForUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.follower = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти подписчиков конкретного пользователя
     */
    public function findFollowersForUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.followed = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Проверить, подписан ли один пользователь на другого
     */
    public function isUserFollowing(User $follower, User $followed): bool
    {
        $subscription = $this->createQueryBuilder('s')
            ->andWhere('s.follower = :follower')
            ->andWhere('s.followed = :followed')
            ->setParameter('follower', $follower)
            ->setParameter('followed', $followed)
            ->getQuery()
            ->getOneOrNullResult();

        return $subscription !== null;
    }
}
