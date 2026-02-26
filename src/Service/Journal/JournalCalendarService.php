<?php

namespace App\Service\Journal;

use App\Entity\User;
use App\Repository\JournalEntryRepository;

class JournalCalendarService
{
    /** @var JournalEntryRepository */
    private $repository;

    public function __construct(JournalEntryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Возвращает готовый контекст календаря месяца для Twig
     */
    public function buildCalendar(User $user, ?string $month): array
    {
        // 1. Текущий месяц
        if ($month) {
            $currentMonth = \DateTimeImmutable::createFromFormat('Y-m', $month)
                ->modify('first day of this month');
        } else {
            $currentMonth = new \DateTimeImmutable('first day of this month');
        }

        // 2. Диапазон календаря (пн–вс)
        $start = $currentMonth->modify('monday this week');
        $end   = $currentMonth
            ->modify('last day of this month')
            ->modify('sunday this week');

        // 3. Получаем записи пользователя за диапазон
        $entries = $this->repository->createQueryBuilder('e')
            ->where('e.user = :user')
            ->andWhere('e.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        // 4. Индексация записей по дате
        $entriesByDate = [];
        foreach ($entries as $entry) {
            $entriesByDate[$entry->getDate()->format('Y-m-d')] = true;
        }

        // 5. Формируем список дней календаря
        $period = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end->modify('+1 day')
        );

        $days = [];
        foreach ($period as $day) {
            $days[] = $day;
        }

        return [
            'currentMonth'  => $currentMonth,
            'days'          => $days,
            'entriesByDate' => $entriesByDate,
            'today'         => new \DateTimeImmutable('today'),
        ];
    }

    /**
     * Возвращает неделю (пн–вс) для заданной даты-якоря
     */
    public function buildWeek(User $user, ?\DateTimeImmutable $anchor = null): array
    {
        $anchor = $anchor ?? new \DateTimeImmutable('today');

        // Неделя по ISO — с понедельника
        $startOfWeek = $anchor->modify('monday this week');
        $endOfWeek   = $startOfWeek->modify('+6 days');

        // Записи пользователя за неделю
        $entries = $this->repository->createQueryBuilder('j')
            ->where('j.user = :user')
            ->andWhere('j.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->getQuery()
            ->getResult();

        // Индексация по дате
        $entriesByDate = [];
        foreach ($entries as $entry) {
            $entriesByDate[$entry->getDate()->format('Y-m-d')] = true;
        }

        // Формируем 7 дней недели
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->modify('+' . $i . ' days');
            $key = $day->format('Y-m-d');

            $weekDays[] = [
                'date'     => $day,
                'dateKey'  => $key,
                'isToday'  => $key === (new \DateTimeImmutable('today'))->format('Y-m-d'),
                'hasEntry' => isset($entriesByDate[$key]),
            ];
        }

        $isWeekComplete = true;
        foreach ($weekDays as $day) {
            if (!$day['hasEntry']) {
                $isWeekComplete = false;
                break;
            }
        }

        return [
            'weekStart' => $startOfWeek,
            'weekEnd'   => $endOfWeek,
            'weekDays'  => $weekDays,
            'isWeekComplete' => $isWeekComplete,

            // 👇 КЛЮЧЕВО ДЛЯ СТРЕЛОК
            'prevWeek'  => $startOfWeek->modify('-7 days'),
            'nextWeek'  => $startOfWeek->modify('+7 days'),
        ];
    }
}