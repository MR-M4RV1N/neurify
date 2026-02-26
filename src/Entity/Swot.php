<?php

namespace App\Entity;

use App\Repository\SwotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SwotRepository::class)
 */
class Swot
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $strengths;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $weaknesses;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $opportunities;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $threats;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

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

    public function getStrengths(): ?string
    {
        return $this->strengths;
    }

    public function setStrengths(?string $strengths): self
    {
        $this->strengths = $strengths;

        return $this;
    }

    public function getWeaknesses(): ?string
    {
        return $this->weaknesses;
    }

    public function setWeaknesses(?string $weaknesses): self
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }

    public function getOpportunities(): ?string
    {
        return $this->opportunities;
    }

    public function setOpportunities(?string $opportunities): self
    {
        $this->opportunities = $opportunities;

        return $this;
    }

    public function getThreats(): ?string
    {
        return $this->threats;
    }

    public function setThreats(?string $threats): self
    {
        $this->threats = $threats;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
