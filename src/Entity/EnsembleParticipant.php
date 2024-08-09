<?php

namespace App\Entity;

use App\Repository\EnsembleParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnsembleParticipantRepository::class)
 */
class EnsembleParticipant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ensemble::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ensemble;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnsemble(): ?Ensemble
    {
        return $this->ensemble;
    }

    public function setEnsemble(?Ensemble $ensemble): self
    {
        $this->ensemble = $ensemble;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
