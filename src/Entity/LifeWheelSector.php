<?php

namespace App\Entity;

use App\Repository\LifeWheelSectorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LifeWheelSectorRepository::class)
 */
class LifeWheelSector
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function getIcon(): string
    {
        // Простой маппинг по названию (можно расширить)
        $map = [
            'Health'      => 'favorite',
            'Здоровье'    => 'favorite',
            'Career'      => 'work',
            'Карьера'     => 'work',
            'Money'       => 'attach_money',
            'Деньги'      => 'attach_money',
            'Family'      => 'people',
            'Семья'       => 'people',
            'Friends'     => 'groups',
            'Друзья'      => 'groups',
            'Self-Growth' => 'trending_up',
            'Self-growth' => 'trending_up',
            'Саморазвитие' => 'trending_up',
            'Spirituality' => 'spa',
            'Духовность'  => 'spa',
            'Recreation'  => 'beach_access',
            'Отдых'       => 'beach_access',
            'Environment' => 'public',
            'Окружение'   => 'public',
            'Community'   => 'forum',
            'Сообщество'  => 'forum',
            'Appearance'  => 'face',
            'Внешность'   => 'face',
            'Love'        => 'favorite_border',
            'Любовь'      => 'favorite_border',
        ];

        return $map[$this->name] ?? 'category';
    }
}
