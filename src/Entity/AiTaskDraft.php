<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\AiTaskDraftRepository::class)
 */
class AiTaskDraft
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\ManyToOne(targetEntity=User::class) */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Ensemble::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $ensemble;

    /** @ORM\Column(type="text") */
    private $comment;

    /** @ORM\Column(type="text") */
    private $tasksJson;

    /** @ORM\Column(type="text", nullable=true) */
    private $notes;

    /** @ORM\Column(type="datetime") */
    private $createdAt;

    /** @ORM\Column(type="datetime") */
    private $updatedAt;

    /** @ORM\Column(type="boolean") */
    private $isSaved = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $strategy;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $lang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
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

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getTasksJson(): string
    {
        return $this->tasksJson;
    }

    public function setTasksJson(string $json): self
    {
        $this->tasksJson = $json;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $dt): self
    {
        $this->createdAt = $dt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $dt): self
    {
        $this->updatedAt = $dt;
        return $this;
    }

    public function isSaved(): bool
    {
        return $this->isSaved;
    }

    public function setIsSaved(bool $saved): self
    {
        $this->isSaved = $saved;
        return $this;
    }

    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    public function setStrategy(?string $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }
}
