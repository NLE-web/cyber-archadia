<?php

namespace App\Entity;

use App\Repository\KeywordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeywordRepository::class)]
class Keyword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    #[ORM\Column(length: 255)]
    private ?string $display = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'keywords')]
    private Collection $items;

    /**
     * @var Collection<int, Feat>
     */
    #[ORM\ManyToMany(targetEntity: Feat::class, inversedBy: 'keywords')]
    private Collection $feats;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\ManyToMany(targetEntity: Action::class, inversedBy: 'keywords')]
    private Collection $actions;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'keywords')]
    private Collection $skills;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->feats = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): static
    {
        $this->display = $display;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return Collection<int, Feat>
     */
    public function getFeats(): Collection
    {
        return $this->feats;
    }

    public function addFeat(Feat $feat): static
    {
        if (!$this->feats->contains($feat)) {
            $this->feats->add($feat);
        }

        return $this;
    }

    public function removeFeat(Feat $feat): static
    {
        $this->feats->removeElement($feat);

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        $this->actions->removeElement($action);

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->display;
    }
}
