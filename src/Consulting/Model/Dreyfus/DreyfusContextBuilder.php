<?php

namespace App\Consulting\Model\Dreyfus;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DreyfusContextBuilder
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Собирает контекст пользователя для Dreyfus-модели
     */
    public function build(User $user): array
    {
        // 1. Профиль
        $profileDescription = (string) $user->getDescription();

        // 2. События за последний месяц
        $events = $this->em
            ->getRepository(Event::class)
            ->getLastMonthEventsByUser($user);

        $eventsPayload = [];
        foreach ($events as $event) {
            $eventsPayload[] = [
                'title'       => (string) $event->getTitle(),
                'description' => (string) $event->getDescription(),
            ];
        }

        // 3. Краткое представление событий (для prompt)
        $eventsBrief = $this->formatEventsForPrompt($eventsPayload);

        return [
            'profile'        => $profileDescription,
            'eventsBrief'    => $eventsBrief,
            'eventsSnapshot' => $eventsPayload,
        ];
    }

    // ======================
    // Helpers
    // ======================

    private function formatEventsForPrompt(array $events): string
    {
        if (!$events) {
            return 'No recent events.';
        }

        $lines = [];
        $i = 0;

        foreach ($events as $e) {
            $i++;

            $title = isset($e['title'])
                ? $this->normalize($e['title'], 100)
                : '';

            $desc = isset($e['description'])
                ? $this->normalize($e['description'], 180)
                : '';

            $lines[] = $i . '. ' . $title . ' — ' . $desc;

            if ($i >= 10) {
                break;
            }
        }

        return implode("\n", $lines);
    }

    private function normalize($text, $limit)
    {
        $t = (string) $text;
        $t = strip_tags($t);
        $t = preg_replace('/\s+/u', ' ', $t);
        $t = trim($t);

        if (mb_strlen($t, 'UTF-8') > $limit) {
            $t = mb_substr($t, 0, $limit, 'UTF-8') . '…';
        }

        return $t;
    }
}