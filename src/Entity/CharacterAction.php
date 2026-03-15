<?php

namespace App\Entity;

use App\Repository\CharacterActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterActionRepository::class)]
class CharacterAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne]
    private ?Action $action = null;

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

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): static
    {
        $this->action = $action;

        return $this;
    }
}
