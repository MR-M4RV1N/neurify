<?php

namespace App\Service;

use App\Entity\Ensemble;
use App\Entity\Event;
use App\Entity\EventRepeated;
use App\Entity\EnsembleComplete;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class EnsembleProgressService
{
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * Получить выбранный Ensemble автора
     */
    private function getSelectedEnsemble(User $author): ?Ensemble
    {
        return $this->em->getRepository(Ensemble::class)->findOneBy([
            'author'   => $author,
            'selected' => true,
        ]);
    }

    /**
     * Получить список ЗАДАНИЙ (Event), где task = true
     */
    private function getTasks(Ensemble $ensemble): array
    {
        return $this->em->getRepository(Event::class)->findBy([
            'ensemble' => $ensemble,
            'task'     => true,
        ]);
    }

    /**
     * Получить выполненные задания только для одного исполнителя
     */
    private function getCompletedTaskMap(User $performer, Ensemble $ensemble): array
    {
        $records = $this->em->getRepository(EventRepeated::class)->findBy([
            'user' => $performer,
        ]);

        $map = [];

        foreach ($records as $r) {

            // берем только задания из этого Ensemble
            if ($r->getReplyEvent() &&
                $r->getEvent()->getEnsemble()->getId() === $ensemble->getId()) {

                $map[$r->getEvent()->getId()] = true;
            }
        }

        return $map;
    }

    /**
     * Главная функция:
     * $performer — кто выполняет задания
     * $author — чьи задания проверяем (владелец Ensemble)
     */
    public function checkProgress(
        User $performer,
        User $author,
        ?Ensemble $ensemble = null
    ): int {
        // 1. Если Ensemble не передан — берём активный Ensemble автора
        if (!$ensemble) {
            $ensemble = $this->getSelectedEnsemble($author);

            if (!$ensemble) {
                return 0;
            }
        }

        // 2. Берём список заданий автора
        $tasks = $this->getTasks($ensemble);

        if (count($tasks) === 0) {
            return 0;
        }

        // 3. Получаем выполненные задания от исполнителя
        $completedMap = $this->getCompletedTaskMap($performer, $ensemble);

        // 4. Проверяем: все ли задания выполнены?
        foreach ($tasks as $task) {
            if (!isset($completedMap[$task->getId()])) {
                return 0;
            }
        }

        // 5. Все задания выполнены → проверяем EnsembleComplete
        $exists = $this->em->getRepository(EnsembleComplete::class)->findOneBy([
            'user'     => $performer,
            'ensemble' => $ensemble,
        ]);

        if (!$exists) {
            $completion = new EnsembleComplete();
            $completion->setUser($performer);
            $completion->setEnsemble($ensemble);
            $completion->setCompletedAt(new \DateTimeImmutable());

            $this->em->persist($completion);
            $this->em->flush();

            return 1;
        }

        return 0;
    }
}