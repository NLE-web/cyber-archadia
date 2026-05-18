<?php

namespace App\Entity;

use App\Repository\EdgeRunnerDownTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EdgeRunnerDownTimeRepository::class)]
class EdgeRunnerDownTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'edgeRunnerDownTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DownTime $downtime = null;

    #[ORM\ManyToOne(inversedBy: 'edgeRunnerDownTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Edgerunner $edgerunner = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $effetBonus = null;

    #[ORM\Column]
    private bool $draft = false;

    #[ORM\Column]
    private bool $discard = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDowntime(): ?DownTime
    {
        return $this->downtime;
    }

    public function setDowntime(?DownTime $downtime): static
    {
        $this->downtime = $downtime;

        return $this;
    }

    public function getEdgerunner(): ?Edgerunner
    {
        return $this->edgerunner;
    }

    public function setEdgerunner(?Edgerunner $edgerunner): static
    {
        $this->edgerunner = $edgerunner;

        return $this;
    }

    public function getEffetBonus(): ?string
    {
        return $this->effetBonus;
    }

    public function setEffetBonus(?string $effetBonus): static
    {
        $this->effetBonus = $effetBonus;

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): static
    {
        $this->draft = $draft;

        return $this;
    }

    public function isDiscard(): bool
    {
        return $this->discard;
    }

    public function setDiscard(bool $discard): static
    {
        $this->discard = $discard;

        return $this;
    }
}
