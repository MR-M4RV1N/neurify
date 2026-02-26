<?php

namespace App\Service;

use App\Entity\Ensemble;
use App\Entity\EnsembleParticipant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EnsembleParticipantService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addParticipant(Ensemble $ensemble, User $user): bool
    {
        $existingParticipant = $this->entityManager->getRepository(EnsembleParticipant::class)
            ->findOneBy(['ensemble' => $ensemble->getId(), 'user' => $user]);

        if ($existingParticipant) {
            return false;
        }

        $participant = new EnsembleParticipant();
        $participant->setEnsemble($ensemble);
        $participant->setUser($user);
        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        return true;
    }

    public function removeParticipant(Ensemble $ensemble, User $user): void
    {
        $participant = $this->entityManager->getRepository(EnsembleParticipant::class)
            ->findOneBy(['ensemble' => $ensemble->getId(), 'user' => $user]);

        if ($participant) {
            $this->entityManager->remove($participant);
            $this->entityManager->flush();
        }
    }
}
