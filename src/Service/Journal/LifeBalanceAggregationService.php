<?php

namespace App\Service\Journal;

use App\Entity\User;
use App\Entity\JournalWeekComment;
use Doctrine\ORM\EntityManagerInterface;

class LifeBalanceAggregationService
{
    private const LIFE_KEYS = [
        'Love',
        'Family',
        'Friends',
        'Career',
        'Money',
        'Self-Growth',
        'Spirituality',
        'Recreation',
        'Environment',
        'Community',
        'Health',
        'Appearance',
    ];

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Суммирует life_balance по последним $limit недельным комментариям.
     *
     * Правила честности:
     * - если life_balance отсутствует/не массив => неделю пропускаем
     * - если категория отсутствует => считаем 0
     * - значения "зажимаем" в диапазон 0..5 (0 — на случай отсутствия/мусора)
     *
     * @return array{
     *   totals: array<string,int>,
     *   weeksRequested: int,
     *   weeksUsed: int,
     *   weeksSkipped: int
     * }
     */
    public function aggregateLastWeeks(User $user, int $limit = 12): array
    {
        $weeks = $this->findLastWeeks($user, $limit);

        $totals = array_fill_keys(self::LIFE_KEYS, 0);

        $used = 0;
        $skipped = 0;

        foreach ($weeks as $weekComment) {
            $ai = $weekComment->getAiAnalysis();

            if (!is_array($ai)) {
                $skipped++;
                continue;
            }

            $lb = $ai['life_balance'] ?? null;
            if (!is_array($lb)) {
                $skipped++;
                continue;
            }

            // неделя валидна (есть life_balance)
            $used++;

            foreach (self::LIFE_KEYS as $key) {
                $value = $lb[$key] ?? 0;

                if (!is_numeric($value)) {
                    $value = 0;
                }

                $v = (int) $value;

                // честная шкала: 0..5 (0 — если нет/мусор)
                if ($v < 0) { $v = 0; }
                if ($v > 5) { $v = 5; }

                $totals[$key] += $v;
            }
        }

        return [
            'totals'         => $totals,
            'weeksRequested' => $limit,
            'weeksUsed'      => $used,
            'weeksSkipped'   => $skipped,
        ];
    }

    /**
     * @return JournalWeekComment[]
     */
    private function findLastWeeks(User $user, int $limit): array
    {
        return $this->em->getRepository(JournalWeekComment::class)
            ->createQueryBuilder('w')
            ->andWhere('w.user = :user')
            ->andWhere('w.aiAnalysis IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('w.weekStart', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * На будущее: если захочешь использовать список ключей снаружи (UI/график).
     *
     * @return string[]
     */
    public function getLifeKeys(): array
    {
        return self::LIFE_KEYS;
    }
}