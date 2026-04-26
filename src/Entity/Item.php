<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    public const TYPE_CONSOMMABLE = 'consommable';
    public const TYPE_EQUIPEMENT = 'equipement';
    public const TYPE_DIVERS = 'divers';

    public const TYPE_CYBERWARE = 'cyberware';

    public const TYPES = [
        self::TYPE_CONSOMMABLE => 'Consommable',
        self::TYPE_EQUIPEMENT => 'Équipement',
        self::TYPE_DIVERS => 'Divers',
        self::TYPE_CYBERWARE => 'Cyberware',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne]
    private ?ImageFile $illustration = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $isConsume = false;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $price = 0;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $chargePrice = 0;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, CharacterItem>
     */
    #[ORM\OneToMany(targetEntity: CharacterItem::class, mappedBy: 'item', cascade: ['remove'])]
    private Collection $characterItems;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\OneToMany(targetEntity: Action::class, mappedBy: 'item', cascade: ['remove'])]
    private Collection $actions;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $isLegal = true;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $isCumbersome = false;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $isInfiniteStock = false;

    #[ORM\Column(nullable: true)]
    private ?int $humanityLoss = null;

    public function __construct()
    {
        $this->characterItems = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function isInfiniteStock(): ?bool
    {
        return $this->isInfiniteStock;
    }

    public function setIsInfiniteStock(bool $isInfiniteStock): static
    {
        $this->isInfiniteStock = $isInfiniteStock;

        return $this;
    }

    public function getHumanityLoss(): ?int
    {
        return $this->humanityLoss;
    }

    public function setHumanityLoss(?int $humanityLoss): static
    {
        $this->humanityLoss = $humanityLoss;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIllustration(): ?ImageFile
    {
        return $this->illustration;
    }

    public function setIllustration(?ImageFile $illustration): static
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function isConsume(): ?bool
    {
        return $this->isConsume;
    }

    public function setIsConsume(bool $isConsume): static
    {
        $this->isConsume = $isConsume;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getChargePrice(): ?int
    {
        return $this->chargePrice;
    }

    public function setChargePrice(int $chargePrice): static
    {
        $this->chargePrice = $chargePrice;

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
     * @return Collection<int, CharacterItem>
     */
    public function getCharacterItems(): Collection
    {
        return $this->characterItems;
    }

    public function addCharacterItem(CharacterItem $characterItem): static
    {
        if (!$this->characterItems->contains($characterItem)) {
            $this->characterItems->add($characterItem);
            $characterItem->setItem($this);
        }

        return $this;
    }

    public function removeCharacterItem(CharacterItem $characterItem): static
    {
        if ($this->characterItems->removeElement($characterItem)) {
            // set the owning side to null (unless already changed)
            if ($characterItem->getItem() === $this) {
                $characterItem->setItem(null);
            }
        }

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
            $action->setItem($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getItem() === $this) {
                $action->setItem(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Objet';
    }

    public function isLegal(): ?bool
    {
        return $this->isLegal;
    }

    public function setIsLegal(bool $isLegal): static
    {
        $this->isLegal = $isLegal;

        return $this;
    }

    public function isCumbersome(): ?bool
    {
        return $this->isCumbersome;
    }

    public function setIsCumbersome(bool $isCumbersome): static
    {
        $this->isCumbersome = $isCumbersome;

        return $this;
    }
}
