<?php

namespace App\Entity;

use App\Repository\ImageSimpleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageSimpleRepository::class)
 */
class ImageSimple
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Simple::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $simple;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSimple(): ?Simple
    {
        return $this->simple;
    }

    public function setSimple(?Simple $simple): self
    {
        $this->simple = $simple;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
