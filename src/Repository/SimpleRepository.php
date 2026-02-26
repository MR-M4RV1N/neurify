<?php

namespace App\Repository;

use App\Entity\Simple;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Simple>
 *
 * @method Simple|null find($id, $lockMode = null, $lockVersion = null)
 * @method Simple|null findOneBy(array $criteria, array $orderBy = null)
 * @method Simple[]    findAll()
 * @method Simple[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimpleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Simple::class);
    }

    public function add(Simple $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Simple $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createSimpleQueryBuilder(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.images', 'i') // Подключаем связанные изображения
            ->addSelect('i') // Выбираем изображения для загрузки
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function createSimplePortfolioQueryBuilder(User $user, $selectedEnsemble = null)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.images', 'i') // Подключаем связанные изображения
            ->addSelect('i') // Выбираем изображения для загрузки
            ->where('e.user = :user')
            ->setParameter('user', $user)
//            ->andWhere('e.portfolio = :portfolio')
//            ->setParameter('portfolio', true)
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $selectedEnsemble)
            ->getQuery()
            ->getResult();
    }

    public function createDefaultSimpleQueryBuilder($user)
    {
        // Выбрать все события, где ensemble = null и user = $user
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.ensemble IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function createGroupSimpleQueryBuilder($ensemble)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $ensemble)
            ->getQuery()
            ->getResult();
    }

    public function createPublicSimpleQueryBuilder($public)
    {
        return $this->createQueryBuilder('e')
            ->where('e.public = :public')
            ->setParameter('public', $public)
            ->getQuery()
            ->getResult();
    }

    public function createPublicSimpleFromUsersQueryBuilder($user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.public = :public')
            ->setParameter('public', true)
            ->getQuery()
            ->getResult();
    }

    public function createSubscribeSimpleQueryBuilder($public, $userId)
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

//    /**
//     * @return Simple[] Returns an array of Simple objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Simple
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
