<?php

namespace App\Repository\NeuralDiveBot;

use App\Entity\NeuralDiveBot\NeuralDivePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NeuralDivePost>
 *
 * @method NeuralDivePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method NeuralDivePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method NeuralDivePost[]    findAll()
 * @method NeuralDivePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeuralDivePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NeuralDivePost::class);
    }

    public function add(NeuralDivePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NeuralDivePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NeuralDivePost[] Returns an array of NeuralDivePost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NeuralDivePost
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
