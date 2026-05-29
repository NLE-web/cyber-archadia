<?php

namespace App\Entity;

use App\Repository\FeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeatRepository::class)]
class Feat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\ManyToMany(targetEntity: Action::class)]
    private Collection $actions;

    /**
     * @var Collection<int, Keyword>
     */
    #[ORM\ManyToMany(targetEntity: Keyword::class, mappedBy: 'feats')]
    private Collection $keywords;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }

    public function getXpcost(): ?int
    {
        return $this->xpcost;
    }

    public function setXpcost(?int $xpcost): static
    {
        $this->xpcost = $xpcost;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
            $keyword->addFeat($this);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        if ($this->keywords->removeElement($keyword)) {
            $keyword->removeFeat($this);
        }

        return $this;
    }

    #[ORM\Column(nullable: true)]
    private ?int $xpcost = null;

    public function __toString(): string
    {
        return $this->name ?? 'Feat';
    }
}
