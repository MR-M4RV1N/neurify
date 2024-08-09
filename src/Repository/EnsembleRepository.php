<?php

namespace App\Repository;

use App\Entity\Ensemble;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ensemble>
 *
 * @method Ensemble|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ensemble|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ensemble[]    findAll()
 * @method Ensemble[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnsembleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ensemble::class);
    }

    public function add(Ensemble $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ensemble $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createEnsamblesQueryBuilder($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.is_closed = false')
            ->andWhere('e.author != :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC');
    }

    public function createEnsamblesQueryBuilderClosed($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.author = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC');
    }

//    /**
//     * @return Ensemble[] Returns an array of Ensemble objects
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

//    public function findOneBySomeField($value): ?Ensemble
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
