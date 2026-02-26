<?php

namespace App\Entity;

use App\Repository\AiCareerConsultingResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AiCareerConsultingResultRepository::class)
 */
class AiCareerConsultingResult
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
     * @ORM\Column(type="integer")
     */
    private $careerLevel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $levelName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $analysisJson = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $eventsSnapshot = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $profileSnapshot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $previousLevel;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $generatedTasks;

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

    public function getCareerLevel(): ?int
    {
        return $this->careerLevel;
    }

    public function setCareerLevel(int $careerLevel): self
    {
        $this->careerLevel = $careerLevel;

        return $this;
    }

    public function getLevelName(): ?string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): self
    {
        $this->levelName = $levelName;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAnalysisJson(): ?array
    {
        return $this->analysisJson;
    }

    public function setAnalysisJson(?array $analysisJson): self
    {
        $this->analysisJson = $analysisJson;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getEventsSnapshot(): ?array
    {
        return $this->eventsSnapshot;
    }

    public function setEventsSnapshot(?array $eventsSnapshot): self
    {
        $this->eventsSnapshot = $eventsSnapshot;

        return $this;
    }

    public function getProfileSnapshot(): ?string
    {
        return $this->profileSnapshot;
    }

    public function setProfileSnapshot(?string $profileSnapshot): self
    {
        $this->profileSnapshot = $profileSnapshot;

        return $this;
    }

    public function getPreviousLevel(): ?int
    {
        return $this->previousLevel;
    }

    public function setPreviousLevel(?int $previousLevel): self
    {
        $this->previousLevel = $previousLevel;

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

    public function isGeneratedTasks(): ?bool
    {
        return $this->generatedTasks;
    }

    public function setGeneratedTasks(bool $generatedTasks): self
    {
        $this->generatedTasks = $generatedTasks;

        return $this;
    }
}
