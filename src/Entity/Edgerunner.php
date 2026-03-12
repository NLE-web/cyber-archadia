<?php

namespace App\Entity;

use App\Repository\EdgerunnerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EdgerunnerRepository::class)]
class Edgerunner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $force = null;

    #[ORM\Column]
    private ?int $dexterite = null;

    #[ORM\Column]
    private ?int $intelligence = null;

    #[ORM\Column]
    private ?int $lifepoints = null;

    #[ORM\Column]
    private ?int $cyberpoints = null;

    #[ORM\Column]
    private ?int $stresspoints = null;

    #[ORM\ManyToOne(inversedBy: 'edgerunners')]
    private ?User $player = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ImageFile $avatar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getForce(): ?int
    {
        return $this->force;
    }

    public function setForce(int $force): static
    {
        $this->force = $force;

        return $this;
    }

    public function getDexterite(): ?int
    {
        return $this->dexterite;
    }

    public function setDexterite(int $dexterite): static
    {
        $this->dexterite = $dexterite;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): static
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getLifepoints(): ?int
    {
        return $this->lifepoints;
    }

    public function setLifepoints(int $lifepoints): static
    {
        $this->lifepoints = $lifepoints;

        return $this;
    }

    public function getCyberpoints(): ?int
    {
        return $this->cyberpoints;
    }

    public function setCyberpoints(int $cyberpoints): static
    {
        $this->cyberpoints = $cyberpoints;

        return $this;
    }

    public function getStresspoints(): ?int
    {
        return $this->stresspoints;
    }

    public function setStresspoints(int $stresspoints): static
    {
        $this->stresspoints = $stresspoints;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getAvatar(): ?ImageFile
    {
        return $this->avatar;
    }

    public function setAvatar(?ImageFile $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }
}
