<?php

namespace App\Repository;

use App\Entity\MatrixMap;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatrixMap>
 *
 * @method MatrixMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatrixMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatrixMap[]    findAll()
 * @method MatrixMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatrixMapRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatrixMap::class);
    }

    public function add(MatrixMap $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MatrixMap $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createPublicMatrixMapFromUsersQueryBuilder($user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.public = :public')
            ->setParameter('public', true)
            ->getQuery()
            ->getResult();
    }

    public function createSubscribeMatrixMapQueryBuilder($public, $userId)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.user', 'u') // соединяем с пользователем, создавшим событие
            ->innerJoin('u.followers', 's', 'WITH', 's.follower = :userId') // добавляем связь подписки
            ->where('e.public = :public')
            ->setParameter('public', $public)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function createPublicMatrixMapQueryBuilder($public)
    {
        return $this->createQueryBuilder('e')
            ->where('e.public = :public')
            ->setParameter('public', $public)
            ->getQuery()
            ->getResult();
    }

    public function createGroupMatrixMapQueryBuilder($ensemble)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $ensemble)
            ->getQuery()
            ->getResult();
    }

    public function createDefaultMatrixMapQueryBuilder($user)
    {
        // Выбрать все события, где ensemble = null и user = $user
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.ensemble IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function createMatrixMapQueryBuilder(User $user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function createMatrixMapPortfolioQueryBuilder(User $user, $selectedEnsemble = null)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
//            ->andWhere('e.portfolio = :portfolio')
//            ->setParameter('portfolio', true)
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $selectedEnsemble)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return MatrixMap[] Returns an array of Maps objects
     */
    public function findUserWithPaginator(int $offset, $user): Paginator
    {
        $query = $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }

//    /**
//     * @return MatrixMap[] Returns an array of MatrixMap objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MatrixMap
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
