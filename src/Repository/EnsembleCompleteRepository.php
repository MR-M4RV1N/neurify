<?php

namespace App\Repository;

use App\Entity\EnsembleComplete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnsembleComplete>
 *
 * @method EnsembleComplete|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnsembleComplete|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnsembleComplete[]    findAll()
 * @method EnsembleComplete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnsembleCompleteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnsembleComplete::class);
    }

    public function add(EnsembleComplete $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EnsembleComplete $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EnsembleComplete[] Returns an array of EnsembleComplete objects
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

//    public function findOneBySomeField($value): ?EnsembleComplete
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
