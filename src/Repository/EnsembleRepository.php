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

    public function createPublicEnsamblesQueryBuilder($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.is_closed = false')
            ->andWhere('e.author != :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC');
    }

    public function createEnsamblesQueryBuilder($value)
    {
        return $this->createQueryBuilder('e')
            ->select(
                'e AS ensemble',
                'COUNT(DISTINCT ev.id) AS eventCount',
                'COUNT(DISTINCT m.id) AS matrixCount',
                'COUNT(DISTINCT s.id) AS simpleCount',
                'COUNT(DISTINCT p.id) AS pictureCount',
                '(COUNT(DISTINCT ev.id) + COUNT(DISTINCT m.id) + COUNT(DISTINCT s.id) + COUNT(DISTINCT p.id)) AS totalCount'
            )
            ->leftJoin('e.events', 'ev')
            ->leftJoin('App\Entity\MatrixMap', 'm', 'WITH', 'm.ensemble = e')
            ->leftJoin('App\Entity\Simple', 's', 'WITH', 's.ensemble = e')
            ->leftJoin('App\Entity\Picture', 'p', 'WITH', 'p.ensemble = e')
            ->andWhere('e.author = :val')
            ->setParameter('val', $value)
            ->andWhere('e.aiGenerated = false')
            ->groupBy('e.id')
            ->orderBy('totalCount', 'DESC');
    }

    public function createEnsembleStatsQB($author)
    {
        return $this->createQueryBuilder('e')
            ->select('e.id AS id')
            ->addSelect('COUNT(DISTINCT ev.id) AS eventCount')
            ->addSelect('COUNT(DISTINCT m.id)  AS matrixCount')
            ->addSelect('COUNT(DISTINCT s.id)  AS simpleCount')
            ->addSelect('COUNT(DISTINCT p.id)  AS pictureCount')
            ->addSelect('(
            COUNT(DISTINCT ev.id) +
            COUNT(DISTINCT m.id)  +
            COUNT(DISTINCT s.id)  +
            COUNT(DISTINCT p.id)
        ) AS HIDDEN totalCount')
            ->leftJoin('e.events', 'ev')
            ->leftJoin('App\Entity\MatrixMap', 'm', 'WITH', 'm.ensemble = e')
            ->leftJoin('App\Entity\Simple',    's', 'WITH', 's.ensemble = e')
            ->leftJoin('App\Entity\Picture',   'p', 'WITH', 'p.ensemble = e')
            ->andWhere('e.author = :author')
            ->setParameter('author', $author)
            ->groupBy('e.id')
            ->orderBy('totalCount', 'DESC');
    }

    public function createEnsamblesClosedQueryBuilder($value)
    {
        return $this->createQueryBuilder('e')
        ->select(
        'e AS ensemble',
        'COUNT(DISTINCT ev.id) AS eventCount',
        'COUNT(DISTINCT m.id) AS matrixCount',
        'COUNT(DISTINCT s.id) AS simpleCount',
        'COUNT(DISTINCT p.id) AS pictureCount',
        '(COUNT(DISTINCT ev.id) + COUNT(DISTINCT m.id) + COUNT(DISTINCT s.id) + COUNT(DISTINCT p.id)) AS totalCount'
    )
        ->leftJoin('e.events', 'ev')
        ->leftJoin('App\Entity\MatrixMap', 'm', 'WITH', 'm.ensemble = e')
        ->leftJoin('App\Entity\Simple', 's', 'WITH', 's.ensemble = e')
        ->leftJoin('App\Entity\Picture', 'p', 'WITH', 'p.ensemble = e')
        ->andWhere('e.author = :val')
        ->setParameter('val', $value)
        ->andWhere('e.is_closed = false')
        ->groupBy('e.id')
        ->orderBy('totalCount', 'DESC');
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
