<?php

namespace App\Entity;

use App\Repository\EnsembleCompleteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnsembleCompleteRepository::class)
 */
class EnsembleComplete
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Ensemble::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ensemble;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $completedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEnsemble(): ?Ensemble
    {
        return $this->ensemble;
    }

    public function setEnsemble(?Ensemble $ensemble): self
    {
        $this->ensemble = $ensemble;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(\DateTimeImmutable $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }
}
