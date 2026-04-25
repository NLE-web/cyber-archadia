<?php

namespace App\Entity;

use App\Repository\CharacterFeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterFeatRepository::class)]
class CharacterFeat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'feats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Feat $feat = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $xptot = 0;

    public function getXptot(): ?int
    {
        return $this->xptot;
    }

    public function setXptot(int $xptot): static
    {
        $this->xptot = $xptot;

        return $this;
    }

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

    public function getFeat(): ?Feat
    {
        return $this->feat;
    }

    public function setFeat(?Feat $feat): static
    {
        $this->feat = $feat;

        return $this;
    }

    public function __toString(): string
    {
        return $this->feat?->getName() ?? 'Feat inconnu';
    }
}
