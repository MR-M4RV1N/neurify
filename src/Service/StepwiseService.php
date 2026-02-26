<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Ensemble;
use App\Repository\EventRepository;
use App\Repository\EventRepeatedRepository;
use App\Repository\EnsembleRepository;
use App\Repository\SubscriptionRepository;

class StepwiseService
{
    private $subscriptionRepository;
    private $eventRepository;
    private $eventRepeatedRepository;
    private $ensembleRepository;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        EventRepository $eventRepository,
        EventRepeatedRepository $eventRepeatedRepository,
        EnsembleRepository $ensembleRepository
    ) {
        $this->subscriptionRepository   = $subscriptionRepository;
        $this->eventRepository          = $eventRepository;
        $this->eventRepeatedRepository  = $eventRepeatedRepository;
        $this->ensembleRepository       = $ensembleRepository;
    }

    /**
     * Сценарий: открываем страницу по конкретной категории (Ensemble).
     */
    public function buildForEnsemble(Ensemble $ensemble, ?User $currentUser): array
    {
        $author = $ensemble->getAuthor();

        return $this->compute($author, $ensemble, $currentUser);
    }

    /**
     * Сценарий: открываем страницу по автору (User) — выбираем выбранную или первую категорию.
     */
    public function buildForUser(User $user, ?User $currentUser): array
    {
        // 1) Выбранная категория
        $stepwisedCategory = $this->ensembleRepository->findOneBy(['author' => $user, 'selected' => 1]);

        // 1a) Если нет — первая по id ASC
        if (!$stepwisedCategory) {
            $stepwisedCategory = $this->ensembleRepository->findOneBy(['author' => $user], ['id' => 'ASC']);
        }

        return $this->compute($user, $stepwisedCategory, $currentUser);
    }

    /**
     * Общая логика для обоих кейсов.
     */
    private function compute(User $author, ?Ensemble $category, ?User $currentUser): array
    {
        // Статус подписки
        $isFollowing = false;
        if ($currentUser && $currentUser !== $author) {
            $isFollowing = $this->subscriptionRepository->isUserFollowing($currentUser, $author);
        }

        // События
        if ($category) {
            $events = $this->eventRepository->findBy(
                ['user' => $author, 'ensemble' => $category, 'task' => true],
                ['priority' => 'DESC', 'id' => 'ASC']
            );

            $repeatedEvents = $currentUser
                ? $this->eventRepeatedRepository->findCompletedByUserAndEnsemble($currentUser, $category)
                : array();
        } else {
            $events = $this->eventRepository->findBy(
                ['user' => $author, 'task' => true],
                ['priority' => 'DESC', 'id' => 'ASC']
            );

            $repeatedEvents = $currentUser
                ? $this->eventRepeatedRepository->findCompletedByUserForAuthor($currentUser, $author)
                : array();
        }

        // Карта выполненных event_id
        $completedIds = array();
        foreach ($repeatedEvents as $re) {
            $ev = $re->getEvent();
            if ($ev) {
                $completedIds[$ev->getId()] = true;
            }
        }

        // Разложение: выполненные, доступные
        $completedEvents = array();
        $available = array();
        foreach ($events as $ev) {
            if (isset($completedIds[$ev->getId()])) {
                $completedEvents[] = $ev;
            } else {
                $available[] = $ev;
            }
        }

        // Первый доступный + «заблокированные»
        $available = array_values($available);

        // Уровень (как у тебя): 1 уровень = 10 событий, максимум 20
        $allEventsCount = $this->eventRepository->count(['user' => $author]);
        $level = (int) min(floor($allEventsCount / 10), 20);

        return array(
            'completedQuests'   => $completedEvents,
            'todoQuests'        => $available,
            'availableQuest'    => isset($available[0]) ? $available[0] : null,
            'isFollowing'       => $isFollowing,
            'profile'           => $author,
            'level'             => $level,
            'stepwisedCategory' => $category,
        );
    }
}
