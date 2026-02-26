<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function findAllUsersSorted($lang)
    {
        return $this->createQueryBuilder('u') // 'u' - это псевдоним сущности User
        ->where('u.lang = :lang')
        ->setParameter('lang', $lang)
        ->orderBy('u.id', 'DESC') // Замените 'id' на имя поля, по которому нужно сортировать
        ->getQuery()
        ->getResult();
    }

    public function createUsersQueryBuilder()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC');
    }

    public function createUsersProposalQueryBuilder()
    {
        return $this->createQueryBuilder('u');
    }

    public function createUsersByRatingQueryBuilder()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.progress', 'DESC')  // Сортировка по рейтингу
            ->addOrderBy('u.id', 'DESC');     // Сортировка по id на случай одинакового рейтинга
    }

    public function getUserRank(User $user): int
    {
        $qb = $this->createQueryBuilder('u');

        // Запрос на получение позиции текущего пользователя
        $qb->select('COUNT(u)')
            ->andWhere('u.type > 0') // Условие, которое применяли ранее
            ->andWhere('u.progress > :userProgress OR (u.progress = :userProgress AND u.id < :userId)')
            ->setParameter('userProgress', $user->getProgress())
            ->setParameter('userId', $user->getId());

        // Получаем количество пользователей, которые выше текущего
        $rank = $qb->getQuery()->getSingleScalarResult();

        // Ранг пользователя = количество пользователей выше + 1
        return (int) $rank + 1;
    }

    /**
     * Получает список участников вызова (макс. 20), добавляя флаг `completed`
     */
    public function findParticipantsByEvent(int $eventId, int $limit = 20): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
            SELECT u, 
                   CASE WHEN br.id IS NOT NULL THEN true ELSE false END AS completed
            FROM App\Entity\User u
            LEFT JOIN App\Entity\Bookmark b WITH u.id = b.user
            LEFT JOIN App\Entity\BookmarkRegister br WITH u.id = br.user
            WHERE b.event = :eventId OR br.event = :eventId
            GROUP BY u.id
            ORDER BY completed DESC
        ")
            ->setParameter('eventId', $eventId)
            ->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * Возвращает QueryBuilder для пользователей, на которых подписан данный пользователь
     */
    public function createFollowedUsersQueryBuilder(int $userId)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.followers', 's') // Связь с подписками
            ->where('s.follower = :userId')
            ->setParameter('userId', $userId);
    }

    public function findByUsernameLike(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
