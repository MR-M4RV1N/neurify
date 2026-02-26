<?php

namespace App\Repository;

use App\Entity\LifeWheelSector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LifeWheelSector>
 *
 * @method LifeWheelSector|null find($id, $lockMode = null, $lockVersion = null)
 * @method LifeWheelSector|null findOneBy(array $criteria, array $orderBy = null)
 * @method LifeWheelSector[]    findAll()
 * @method LifeWheelSector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LifeWheelSectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LifeWheelSector::class);
    }

    public function add(LifeWheelSector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LifeWheelSector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LifeWheelSector[] Returns an array of LifeWheelSector objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LifeWheelSector
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
