<?php

namespace App\Entity;

use App\Repository\CharacterSkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterSkillRepository::class)]
class CharacterSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Skill $skill = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?int $xp = null;

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

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): static
    {
        $this->skill = $skill;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getXp(): ?int
    {
        return $this->xp;
    }

    public function setXp(int $xp): static
    {
        $this->xp = $xp;

        return $this;
    }

    public function __toString(): string
    {
        return $this->skill?->getName() ?? 'Compétence inconnue';
    }
}
