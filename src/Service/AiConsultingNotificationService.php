<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ConsultingInsightRepository;

class AiConsultingNotificationService
{
    /** @var ConsultingInsightRepository */
    private $results;

    public function __construct(ConsultingInsightRepository $results)
    {
        $this->results = $results;
    }

    /**
     * Возвращает массив:
     * [
     *   'show' => bool,  // показывать ли уведомление
     *   'days' => ?int,  // сколько дней прошло (null, если никогда не было консультации)
     * ]
     */
    public function getNotificationData(?User $user): array
    {
        if (!$user) {
            return [
                'show' => false,
                'days' => null,
            ];
        }

        $last = $this->results->findLastRecordByUser($user);

        // Если консультаций не было — можно считать, что стоит напомнить
        if (!$last) {
            return [
                'show' => true,
                'days' => null,
            ];
        }

        $lastDate = $last->getCreatedAt(); // DateTimeImmutable
        $now = new \DateTimeImmutable('now');

        $days = $now->diff($lastDate)->days;

        return [
            'show' => $days > 7, // показываем, если прошло больше 30 дней
            'days' => $days,
        ];
    }
}