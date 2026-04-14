<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    public const USAGE_ACTION = 'action';
    public const USAGE_RAPIDE = 'rapide';
    public const USAGE_ONCE_PER_TURN = 'once per turn';

    public const USAGE_REACTION = 'reaction';

    public const USAGES = [
        self::USAGE_ACTION => 'Action',
        self::USAGE_RAPIDE => 'Rapide',
        self::USAGE_ONCE_PER_TURN => 'Une fois par tour',
        self::USAGE_REACTION => 'Réaction',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?Item $item = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, CharacterAction>
     */
    #[ORM\OneToMany(targetEntity: CharacterAction::class, mappedBy: 'action', cascade: ['remove'])]
    private Collection $characterActions;

    #[ORM\Column(length: 255)]
    private ?string $usage = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxUse = null;

    #[ORM\Column(nullable: true)]
    private ?int $uses = null;

    public function __construct()
    {
        $this->characterActions = new ArrayCollection();
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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

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

    public function __toString(): string
    {
        return $this->name ?? 'Action';
    }

    public function getUsage(): ?string
    {
        return $this->usage;
    }

    public function setUsage(string $usage): static
    {
        $this->usage = $usage;

        return $this;
    }

    public function getMaxUse(): ?int
    {
        return $this->maxUse;
    }

    public function setMaxUse(?int $maxUse): static
    {
        $this->maxUse = $maxUse;

        return $this;
    }

    public function getUses(): ?int
    {
        return $this->uses;
    }

    public function setUses(?int $uses): static
    {
        $this->uses = $uses;

        return $this;
    }
}
