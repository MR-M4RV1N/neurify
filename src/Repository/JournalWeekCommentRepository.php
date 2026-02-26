<?php

namespace App\Repository;

use App\Entity\JournalWeekComment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JournalWeekComment>
 *
 * @method JournalWeekComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalWeekComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalWeekComment[]    findAll()
 * @method JournalWeekComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalWeekCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JournalWeekComment::class);
    }

    public function add(JournalWeekComment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(JournalWeekComment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForUserAndWeek(User $user, \DateTimeImmutable $weekStart): ?JournalWeekComment
    {
        return $this->findOneBy([
            'user' => $user,
            'weekStart' => $weekStart,
        ]);
    }

    public function findLastWeeks(User $user, int $limit = 12): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :user')
            ->andWhere('w.aiAnalysis IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('w.weekStart', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return JournalWeekComment[] Returns an array of JournalWeekComment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JournalWeekComment
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
