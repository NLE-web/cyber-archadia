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
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne(inversedBy: 'characterItems')]
    private ?Item $item = null;

    #[ORM\Column]
    private ?int $amount = null;

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

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
