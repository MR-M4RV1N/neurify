<?php

namespace App\Repository;

use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findEventsForUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.user', 'u')
            ->innerJoin('u.followers', 'f')
            ->where('f.follower = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Подсчёт событий за последние N дней для конкретного пользователя
     */
    public function countEventsForUserInLastDays(User $user, int $days): int
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('COUNT(e.id)')
            ->where('e.user = :user')
            ->andWhere('e.date >= :date')
            ->setParameter('user', $user)
            ->setParameter('date', new \DateTime("-{$days} days"));

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Получение событий за текущий год для конкретного пользователя, отсортированных по дате в порядке убывания
     *
     * @param int $userId Идентификатор пользователя
     * @return Event[] Возвращает массив событий
     */
    public function findEventsForCurrentYearByUser(int $userId): array
    {
        // Определяем начало и конец текущего года
        $startOfYear = new \DateTime('first day of January 00:00:00');
        $endOfYear = new \DateTime('last day of December 23:59:59');

        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.date BETWEEN :startOfYear AND :endOfYear')
            ->andWhere('e.aiGenerated = :ai_generated')
            ->setParameter('user', $userId)
            ->setParameter('startOfYear', $startOfYear)
            ->setParameter('endOfYear', $endOfYear)
            ->setParameter('ai_generated', false)
            ->orderBy('e.date', 'DESC')
            ->addOrderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function createPublicEventsQueryBuilder($public)
    {
        return $this->createQueryBuilder('e')
            ->where('e.public = :public')
            ->setParameter('public', $public)
            ->getQuery()
            ->getResult();
    }

    /**
     * Создать запрос для выборки публичных событий от пользователей.
     */
    public function createPublicEventsFromUsersQueryBuilder($user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.public = :public')
            ->setParameter('public', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Создать запрос для выборки публичных событий от пользователей, на которых подписан текущий пользователь.
     */
    public function createSubscribeEventsQueryBuilder($public, $userId)
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

    public function createGroupPublicEventsQueryBuilder($public, $ensemble)
    {
        return $this->createQueryBuilder('e')
            ->where('e.public = :public')
            ->setParameter('public', $public)
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $ensemble)
            ->orderBy('e.id', 'DESC');
    }

    public function createGroupEventsQueryBuilder($ensemble)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.ensemble = :ensemble')
            ->setParameter('ensemble', $ensemble)
            ->getQuery()
            ->getResult();
    }

    public function createDefaultEventsQueryBuilder($user)
    {
        // Выбрать все события, где ensemble = null и user = $user
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.ensemble IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function createFeaturedEventsQueryBuilder($user)
    {
        // Выбрать все события, где ensemble = null и user = $user
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.pinned = true')
            ->orderBy('e.date', 'DESC');
    }

    public function createBookmarkEventsQueryBuilder($user)
    {
        // Выбрать все события, где ensemble = null и user = $user
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->andWhere('e.ensemble IS NULL')
            ->orderBy('e.date', 'DESC');
    }

    public function findLastEvents(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findLastEventsWithImages(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.images', 'i')
            ->addSelect('i')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function createEventsQueryBuilder(User $user)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.images', 'i') // Подключаем связанные изображения
            ->addSelect('i') // Выбираем изображения для загрузки
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function createEventsPortfolioQueryBuilder(User $user, $selectedEnsemble = null)
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

    public function createEventsRemainderQueryBuilder($user, $remainder)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->setParameter('user', $user)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults($remainder);
    }

    public function countLastMonthEventsByUser($user)
    {
        $oneMonthAgo = new \DateTime();
        $oneMonthAgo->modify('-1 month');

        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.user = :user')
            ->andWhere('e.date >= :oneMonthAgo')
            ->setParameter('user', $user)
            ->setParameter('oneMonthAgo', $oneMonthAgo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLastMonthEventsByUser($user)
    {
        $oneMonthAgo = new \DateTime();
        $oneMonthAgo->modify('-1 month');

        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.date >= :oneMonthAgo')
            ->andWhere('e.aiGenerated = :aiGenerated')
            ->setParameter('user', $user)
            ->setParameter('oneMonthAgo', $oneMonthAgo)
            ->setParameter('aiGenerated', false)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findNotCompletedEventsByEnsemble(User $user, Ensemble $ensemble): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('App\Entity\EventRepeated', 'er', 'WITH', 'er.event = e.id AND er.user = :user')
            ->where('e.ensemble = :ensemble')
            ->andWhere('er.id IS NULL')
            ->setParameter('user', $user)
            ->setParameter('ensemble', $ensemble)
            ->orderBy('e.priority', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findEventsByUserAndYear(int $userId, int $year): array
    {
        // Если у тебя даты в таймзоне приложения — можно не указывать timezone.
        // Но лучше явно, чтобы не было сюрпризов.
        $tz = new DateTimeZone('Europe/Riga');

        $from = new DateTimeImmutable(sprintf('%d-01-01 00:00:00', $year), $tz);
        $to   = $from->modify('+1 year');

        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->andWhere('e.hidden = false')
            ->andWhere('e.date >= :from')
            ->andWhere('e.date < :to')
            ->setParameter('user', $userId)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countEventsByPowerForUser(User $user): array
    {
        $rows = $this->createQueryBuilder('e')
            ->select('e.power AS power, COUNT(e.id) AS total')
            ->andWhere('e.user = :user')
            ->andWhere('e.hidden = false')
            ->setParameter('user', $user)
            ->groupBy('e.power')
            ->getQuery()
            ->getResult();

        /*
         * Формат:
         * [
         *   3 => 2,
         *   2 => 8,
         *   1 => 5,
         *   0 => 12
         * ]
         */
        $result = [];

        foreach ($rows as $row) {
            $result[(int) $row['power']] = (int) $row['total'];
        }

        // гарантируем наличие всех уровней (0–3)
        for ($i = 0; $i <= 3; $i++) {
            if (!array_key_exists($i, $result)) {
                $result[$i] = 0;
            }
        }

        // ⭐⭐⭐ сначала, без звёзд — в конце
        ksort($result, SORT_NUMERIC);

        return $result;
    }

    public function countEventsByPowerForUserForYear(User $user, int $year): array
    {
        $from = new \DateTimeImmutable($year . '-01-01 00:00:00');
        $to   = new \DateTimeImmutable($year . '-12-31 23:59:59');

        $rows = $this->createQueryBuilder('e')
            ->select('e.power AS power, COUNT(e.id) AS total')
            ->andWhere('e.user = :user')
            ->andWhere('e.hidden = false')
            ->andWhere('e.date BETWEEN :from AND :to') // ← ВАЖНО
            ->setParameter('user', $user)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('e.power')
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($rows as $row) {
            $result[(int) $row['power']] = (int) $row['total'];
        }

        for ($i = 0; $i <= 3; $i++) {
            if (!array_key_exists($i, $result)) {
                $result[$i] = 0;
            }
        }

        ksort($result, SORT_NUMERIC);

        return $result;
    }

    public function findEventsByUserAndPower(User $user, int $power): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->andWhere('e.hidden = false')
            ->andWhere('e.aiGenerated = false')
            ->andWhere('e.power = :power')
            ->setParameter('user', $user)
            ->setParameter('power', $power)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
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

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
