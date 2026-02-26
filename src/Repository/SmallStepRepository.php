<?php

namespace App\Repository;

use App\Entity\SmallStep;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SmallStep>
 *
 * @method SmallStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmallStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmallStep[]    findAll()
 * @method SmallStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmallStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmallStep::class);
    }

    public function add(SmallStep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SmallStep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Текущая неделя + 12 предыдущих (13 недель)
     * Если выбран не текущий год — берём последнюю неделю выбранного года.
     *
     * @return array<int, array{week:string,start:string,count:int}>
     */
    public function countByIsoWeekForUserAndYear(User $user, int $year): array
    {
        $now = new \DateTimeImmutable();

        // anchor: для текущего года — "сейчас", для прошлого года — конец года
        $anchor = ((int)$now->format('Y') === $year)
            ? $now
            : new \DateTimeImmutable($year . '-12-31 12:00:00');

        // Понедельник anchor-недели (ISO)
        $anchorWeekStart = $anchor->modify('monday this week');

        // Начало диапазона: 12 недель назад (т.е. всего 13 недель включая anchor)
        $rangeStart = $anchorWeekStart->modify('-12 week');

        // Конец диапазона (exclusive): +13 недель от rangeStart
        $rangeEndExclusive = $rangeStart->modify('+13 week');

        // Забираем date только в этом окне
        $rows = $this->createQueryBuilder('s')
            ->select('s.date')
            ->andWhere('s.user = :user')
            ->andWhere('s.date >= :start AND s.date < :end')
            ->andWhere('s.draft = :draft')
            ->setParameter('draft', false)
            ->setParameter('user', $user)
            ->setParameter('start', $rangeStart->format('Y-m-d'))
            ->setParameter('end', $rangeEndExclusive->format('Y-m-d'))
            ->getQuery()
            ->getArrayResult();

        // Считаем по ISO-неделям на основе date
        $counts = [];
        foreach ($rows as $r) {
            /** @var \DateTimeInterface $dt */
            $dt = $r['date'];

            $isoYear = (int) $dt->format('o');
            $isoWeek = (int) $dt->format('W');

            $key = sprintf('%d-W%02d', $isoYear, $isoWeek);
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        // Собираем 13 недель в правильном порядке, заполняем пропуски нулями
        $weeks = [];
        for ($i = 0; $i < 13; $i++) {
            $weekStart = $rangeStart->modify('+' . $i . ' week');
            $isoYear = (int) $weekStart->format('o');
            $isoWeek = (int) $weekStart->format('W');
            $key = sprintf('%d-W%02d', $isoYear, $isoWeek);

            $weeks[] = [
                'week'  => sprintf('W%02d', $isoWeek),
                'start' => $weekStart->format('Y-m-d'),
                'count' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $weeks; // от старой недели -> к текущей
    }

    /**
     * Последние 12 дней (12 точек): от старого дня -> к today
     *
     * @return array<int, array{day:string,date:string,count:int}>
     */
    public function countByDayLast12ForUser(User $user): array
    {
        $today = new \DateTimeImmutable('today');            // 00:00 сегодня
        $rangeStart = $today->modify('-11 days');            // включительно
        $rangeEndExclusive = $today->modify('+1 day');       // исключая

        $rows = $this->createQueryBuilder('s')
            ->select('s.date')
            ->andWhere('s.user = :user')
            ->andWhere('s.date >= :start AND s.date < :end')
            ->andWhere('s.draft = :draft')
            ->setParameter('draft', false)
            ->setParameter('user', $user)
            ->setParameter('start', $rangeStart->format('Y-m-d'))
            ->setParameter('end', $rangeEndExclusive->format('Y-m-d'))
            ->getQuery()
            ->getArrayResult();

        // Считаем по дням (ключ = Y-m-d)
        $counts = [];
        foreach ($rows as $r) {
            /** @var \DateTimeInterface $dt */
            $dt = $r['date'];

            $key = $dt->format('Y-m-d');
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        // Собираем 12 дней, заполняем пропуски нулями
        $days = [];
        for ($i = 0; $i < 12; $i++) {
            $d = $rangeStart->modify('+' . $i . ' days');
            $key = $d->format('Y-m-d');

            $days[] = [
                'day'   => $d->format('d.m'),     // например "01.02" (можно иначе)
                'date'  => $key,                  // "2026-02-01"
                'count' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $days;
    }

//    /**
//     * @return SmallStep[] Returns an array of SmallStep objects
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

//    public function findOneBySomeField($value): ?SmallStep
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
