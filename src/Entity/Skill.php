<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $mainStat = null;

    /**
     * @var Collection<int, CharacterSkill>
     */
    #[ORM\OneToMany(targetEntity: CharacterSkill::class, mappedBy: 'skill', cascade: ['remove'])]
    private Collection $characterSkills;

    public function __construct()
    {
        $this->characterSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMainStat(): ?string
    {
        return $this->mainStat;
    }

    public function setMainStat(string $mainStat): static
    {
        $this->mainStat = $mainStat;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Compétence';
    }
}
