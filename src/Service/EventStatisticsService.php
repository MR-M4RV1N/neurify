<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\EventSharedRepository;
use DateTimeImmutable;
use DateTimeZone;

class EventStatisticsService
{
    /** @var EventRepository */
    private $eventRepo;

    /** @var EventSharedRepository */
    private $eventSharedRepo;

    public function __construct(
        EventRepository $eventRepo,
        EventSharedRepository $eventSharedRepo
    ) {
        $this->eventRepo = $eventRepo;
        $this->eventSharedRepo = $eventSharedRepo;
    }

    /**
     * Возвращает (СТАРАЯ СТРУКТУРА):
     * - eventsByMonth:
     *     [
     *       'December 2025' => [ [...], [...]],
     *       'November 2025' => [ [...]],
     *       ...
     *       'January 2025'  => [ [...]]
     *     ]
     */
    public function buildEventsByMonthForUser(User $user, int $year): array
    {
        // --------------------------
        // 1. Диапазон дат года
        // --------------------------
        $tz = new DateTimeZone('Europe/Riga');

        $from = new DateTimeImmutable(sprintf('%d-01-01 00:00:00', $year), $tz);
        $to   = $from->modify('+1 year');

        // --------------------------
        // 2. События пользователя за год
        // --------------------------
        /** @var Event[] $events */
        $events = $this->eventRepo->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->andWhere('e.hidden = false')
            ->andWhere('e.aiGenerated = false')
            ->andWhere('e.aiGenerated = false')
            ->andWhere('e.date >= :from')
            ->andWhere('e.date < :to')
            ->setParameter('user', $user)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();

        // --------------------------
        // 3. ID событий
        // --------------------------
        $eventIds = array_map(static function (Event $event) {
            return $event->getId();
        }, $events);

        // --------------------------
        // 4. Последний EventShared для каждого события
        // --------------------------
        $sharedByEventId = [];

        if (!empty($eventIds)) {
            $sharedList = $this->eventSharedRepo->createQueryBuilder('es')
                ->andWhere('es.event IN (:events)')
                ->andWhere('es.user = :user')
                ->setParameter('events', $eventIds)
                ->setParameter('user', $user)
                ->orderBy('es.createdAt', 'DESC')
                ->getQuery()
                ->getResult();

            foreach ($sharedList as $shared) {
                $eventId = $shared->getEvent()->getId();
                if (!isset($sharedByEventId[$eventId])) {
                    $sharedByEventId[$eventId] = $shared;
                }
            }
        }

        // --------------------------
        // 5. Группируем по месяцам (временно по номеру)
        // --------------------------
        $tmp = [];

        foreach ($events as $event) {
            $monthNumber = (int) $event->getDate()->format('n'); // 1..12
            $monthLabel  = $event->getDate()->format('F Y');

            $image = $event->getImages()->first()
                ? $event->getImages()->first()->getUrl()
                : 'default-min.jpg';

            $eventId = $event->getId();
            $shared  = $sharedByEventId[$eventId] ?? null;

            $tmp[$monthNumber]['label'] = $monthLabel;
            $tmp[$monthNumber]['items'][] = [
                'event'       => $event,
                'image'       => $image,
                'isShared'    => $shared !== null,
                'isPublished' => $shared ? $shared->isPublished() : false,
                'shared'      => $shared,
            ];
        }

        // --------------------------
        // 6. Сортировка месяцев: декабрь → январь
        // --------------------------
        krsort($tmp, SORT_NUMERIC);

        // --------------------------
        // 7. Возвращаем СТАРУЮ СТРУКТУРУ
        // --------------------------
        $eventsByMonth = [];

        foreach ($tmp as $data) {
            $eventsByMonth[$data['label']] = $data['items'];
        }

        return [
            'year'          => $year,
            'events'        => $events,
            'eventsCount'   => count($events),
            'eventsByMonth' => $eventsByMonth,
        ];
    }
}