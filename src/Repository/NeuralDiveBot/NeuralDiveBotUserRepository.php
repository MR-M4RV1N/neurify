<?php

namespace App\Repository\NeuralDiveBot;

use App\Entity\NeuralDiveBot\NeuralDiveBotUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NeuralDiveBotUser>
 *
 * @method NeuralDiveBotUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method NeuralDiveBotUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method NeuralDiveBotUser[]    findAll()
 * @method NeuralDiveBotUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeuralDiveBotUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NeuralDiveBotUser::class);
    }

    public function add(NeuralDiveBotUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NeuralDiveBotUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NeuralDiveBotUser[] Returns an array of NeuralDiveBotUser objects
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

//    public function findOneBySomeField($value): ?NeuralDiveBotUser
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
