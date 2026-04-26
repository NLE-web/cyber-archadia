<?php

namespace App\Entity;

use App\Repository\CharacterItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterItemRepository::class)]
class CharacterItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne(inversedBy: 'characterItems')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Item $item = null;

    #[ORM\Column(nullable: true)]
    private ?int $amount = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isInstalled = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isEquipped = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacter(): ?Edgerunner
    {
        return $this->character;
    }

    public function setCharacter(?Edgerunner $character): static
    {
        $this->character = $character;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function isInstalled(): bool
    {
        return $this->isInstalled;
    }

    public function setIsInstalled(bool $isInstalled): static
    {
        $this->isInstalled = $isInstalled;

        return $this;
    }

    public function isEquipped(): bool
    {
        return $this->isEquipped;
    }

    public function setIsEquipped(bool $isEquipped): static
    {
        $this->isEquipped = $isEquipped;

        return $this;
    }

    public function __toString(): string
    {
        return $this->item?->getName() ?? 'Item inconnu';
    }
}
