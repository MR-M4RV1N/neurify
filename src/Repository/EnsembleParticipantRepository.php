<?php

namespace App\Repository;

use App\Entity\EnsembleParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnsembleParticipant>
 *
 * @method EnsembleParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnsembleParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnsembleParticipant[]    findAll()
 * @method EnsembleParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnsembleParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnsembleParticipant::class);
    }

    public function add(EnsembleParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EnsembleParticipant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EnsembleParticipant[] Returns an array of EnsembleParticipant objects
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

//    public function findOneBySomeField($value): ?EnsembleParticipant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
