<?php

namespace App\Entity;

use App\Repository\EdgerunnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(nullable: true)]
    private ?int $lostlife = null;

    #[ORM\Column]
    private ?int $lostcyber = null;

    /**
     * @var Collection<int, CharacterSkill>
     */
    #[ORM\OneToMany(targetEntity: CharacterSkill::class, mappedBy: 'character')]
    private Collection $skills;

    /**
     * @var Collection<int, CharacterItem>
     */
    #[ORM\OneToMany(targetEntity: CharacterItem::class, mappedBy: 'character')]
    private Collection $items;

    /**
     * @var Collection<int, CharacterAction>
     */
    #[ORM\OneToMany(targetEntity: CharacterAction::class, mappedBy: 'character')]
    private Collection $actions;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }
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

    public function getLostlife(): ?int
    {
        return $this->lostlife;
    }

    public function setLostlife(?int $lostlife): static
    {
        $this->lostlife = $lostlife;

        return $this;
    }

    public function getLostcyber(): ?int
    {
        return $this->lostcyber;
    }

    public function setLostcyber(int $lostcyber): static
    {
        $this->lostcyber = $lostcyber;

        return $this;
    }

    /**
     * @return Collection<int, CharacterSkill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(CharacterSkill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setCharacter($this);
        }

        return $this;
    }

    public function removeSkill(CharacterSkill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getCharacter() === $this) {
                $skill->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CharacterItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCharacter($this);
        }

        return $this;
    }

    public function removeItem(CharacterItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCharacter() === $this) {
                $item->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterAction>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(CharacterAction $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setCharacter($this);
        }

        return $this;
    }

    public function removeAction(CharacterAction $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getCharacter() === $this) {
                $action->setCharacter(null);
            }
        }

        return $this;
    }
}
