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

    #[ORM\Column(type: "float")]
    private ?float $stresspoints = null;

    #[ORM\ManyToOne(inversedBy: 'edgerunners')]
    private ?User $player = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?ImageFile $avatar = null;

    #[ORM\Column(nullable: true)]
    private ?int $lostlife = null;

    #[ORM\Column]
    private ?int $lostcyber = null;

    /**
     * @var Collection<int, CharacterSkill>
     */
    #[ORM\OneToMany(targetEntity: CharacterSkill::class, mappedBy: 'character', cascade: ['persist', 'remove'])]
    private Collection $skills;

    /**
     * @var Collection<int, CharacterItem>
     */
    #[ORM\OneToMany(targetEntity: CharacterItem::class, mappedBy: 'character', cascade: ['persist', 'remove'])]
    private Collection $items;

    /**
     * @var Collection<int, CharacterAction>
     */
    #[ORM\OneToMany(targetEntity: CharacterAction::class, mappedBy: 'character', cascade: ['persist', 'remove'])]
    private Collection $actions;

    /**
     * @var Collection<int, CharacterFeat>
     */
    #[ORM\OneToMany(targetEntity: CharacterFeat::class, mappedBy: 'character', cascade: ['persist', 'remove'])]
    private Collection $feats;

    #[ORM\Column]
    private ?int $money = null;

    #[ORM\Column(options: ["default" => 10])]
    private ?int $xp = 10;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $humanityLoss = 0;

    /**
     * @var Collection<int, Stuff>
     */
    #[ORM\OneToMany(targetEntity: Stuff::class, mappedBy: 'character', cascade: ['persist', 'remove'])]
    private Collection $stuffs;

    /**
     * @var Collection<int, CharacterContact>
     */
    #[ORM\OneToMany(targetEntity: CharacterContact::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $characterContacts;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->feats = new ArrayCollection();
        $this->stuffs = new ArrayCollection();
        $this->characterContacts = new ArrayCollection();
    }
    public function getHumanityLoss(): ?int
    {
        return $this->humanityLoss;
    }

    public function setHumanityLoss(int $humanityLoss): static
    {
        $this->humanityLoss = $humanityLoss;

        return $this;
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

    public function getStresspoints(): ?float
    {
        return $this->stresspoints;
    }

    public function setStresspoints(float $stresspoints): static
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

    public function __toString(): string
    {
        return $this->nom ?? 'Personnage';
    }

    /**
     * @return Collection<int, CharacterFeat>
     */
    public function getFeats(): Collection
    {
        return $this->feats;
    }

    public function addFeat(CharacterFeat $feat): static
    {
        if (!$this->feats->contains($feat)) {
            $this->feats->add($feat);
            $feat->setCharacter($this);
        }

        return $this;
    }

    public function removeFeat(CharacterFeat $feat): static
    {
        if ($this->feats->removeElement($feat)) {
            // set the owning side to null (unless already changed)
            if ($feat->getCharacter() === $this) {
                $feat->setCharacter(null);
            }
        }

        return $this;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(int $money): static
    {
        $this->money = $money;

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

    /**
     * @return Collection<int, Stuff>
     */
    public function getStuffs(): Collection
    {
        return $this->stuffs;
    }

    public function addStuff(Stuff $stuff): static
    {
        if (!$this->stuffs->contains($stuff)) {
            $this->stuffs->add($stuff);
            $stuff->setCharacter($this);
        }

        return $this;
    }

    public function removeStuff(Stuff $stuff): static
    {
        if ($this->stuffs->removeElement($stuff)) {
            // set the owning side to null (unless already changed)
            if ($stuff->getCharacter() === $this) {
                $stuff->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterContact>
     */
    public function getCharacterContacts(): Collection
    {
        return $this->characterContacts;
    }

    public function addCharacterContact(CharacterContact $characterContact): static
    {
        if (!$this->characterContacts->contains($characterContact)) {
            $this->characterContacts->add($characterContact);
            $characterContact->setCharacter($this);
        }

        return $this;
    }

    public function removeCharacterContact(CharacterContact $characterContact): static
    {
        if ($this->characterContacts->removeElement($characterContact)) {
            // set the owning side to null (unless already changed)
            if ($characterContact->getCharacter() === $this) {
                $characterContact->setCharacter(null);
            }
        }

        return $this;
    }
}
