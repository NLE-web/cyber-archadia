<?php

namespace App\Entity;

use App\Repository\ImageFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageFileRepository::class)]
class ImageFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255)]
    private ?string $storageName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getStorageName(): ?string
    {
        return $this->storageName;
    }

    public function setStorageName(string $storageName): static
    {
        $this->storageName = $storageName;

        return $this;
    }
}
