<?php

namespace App\Repository;

use App\Entity\Ensemble;
use App\Entity\EventRepeated;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventRepeated>
 *
 * @method EventRepeated|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRepeated|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRepeated[]    findAll()
 * @method EventRepeated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepeatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRepeated::class);
    }

    public function add(EventRepeated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventRepeated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Все выполненные повторы текущего пользователя в рамках конкретного ансамбля.
     * Возвращает массив сущностей EventRepeated.
     */
    public function findCompletedByUserAndEnsemble(User $user, Ensemble $ensemble)
    {
        return $this->createQueryBuilder('r')
            ->addSelect('e')
            ->join('r.event', 'e')
            ->andWhere('r.user = :user')
            ->andWhere('e.ensemble = :ens')
            ->setParameter('user', $user)
            ->setParameter('ens', $ensemble)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findCompletedByUserForAuthor(User $currentUser, User $author)
    {
        // Все повторы текущего пользователя для событий, созданных указанным автором
        return $this->createQueryBuilder('r')
            ->addSelect('e')
            ->join('r.event', 'e')
            ->andWhere('r.user = :u')
            ->andWhere('e.user = :author')
            ->setParameter('u', $currentUser)
            ->setParameter('author', $author)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCompletedEventsByEnsemble(User $user, Ensemble $ensemble): array
    {
        return $this->createQueryBuilder('er')
            ->join('er.event', 'e')
            ->where('er.user = :user')
            ->andWhere('e.ensemble = :ens')
            ->setParameter('user', $user)
            ->setParameter('ens', $ensemble)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return EventRepeated[] Returns an array of EventRepeated objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventRepeated
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
