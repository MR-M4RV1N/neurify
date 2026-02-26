<?php

namespace App\Repository;

use App\Entity\AiCareerConsultingResult;
use App\Entity\AiConsultingResult;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AiCareerConsultingResult>
 *
 * @method AiCareerConsultingResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method AiCareerConsultingResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method AiCareerConsultingResult[]    findAll()
 * @method AiCareerConsultingResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AiCareerConsultingResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AiCareerConsultingResult::class);
    }

    public function add(AiCareerConsultingResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AiCareerConsultingResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        // Взять последние 3 записи, где пользователь = $user.
        /**
         * @return AiCareerConsultingResult[]  // массив сущностей
         */
        public function findLastRecordsByUser(User $user, int $limit = 3): array
        {
            return $this->createQueryBuilder('a')
                ->andWhere('a.user = :user')
                ->setParameter('user', $user)
                ->orderBy('a.createdAt', 'DESC')
                ->setMaxResults(3)
                ->getQuery()
                ->getResult()
                ;
        }

    public function findLastByUser($user): ?AiConsultingResult
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

//    /**
//     * @return AiCareerConsultingResult[] Returns an array of AiCareerConsultingResult objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AiCareerConsultingResult
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
