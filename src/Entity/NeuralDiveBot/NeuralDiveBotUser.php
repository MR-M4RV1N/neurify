<?php

namespace App\Entity\NeuralDiveBot;

use App\Repository\NeuralDiveBot\NeuralDiveBotUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NeuralDiveBotUserRepository::class)
 */
class NeuralDiveBotUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $userIdNumber;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $readPostsCount = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdNumber(): ?string
    {
        return $this->userIdNumber;
    }

    public function setUserIdNumber(string $userIdNumber): self
    {
        $this->userIdNumber = $userIdNumber;

        return $this;
    }

    public function getReadPostsCount(): ?int
    {
        return $this->readPostsCount;
    }

    public function setReadPostsCount(int $readPostsCount): self
    {
        $this->readPostsCount = $readPostsCount;

        return $this;
    }
}