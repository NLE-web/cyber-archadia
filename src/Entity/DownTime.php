<?php

namespace App\Entity;

use App\Repository\DownTimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DownTimeRepository::class)]
class DownTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $effet = null;

    #[ORM\Column]
    private bool $forced = false;

    #[ORM\Column]
    private ?int $timeCost = null;

    /**
     * @var Collection<int, EdgeRunnerDownTime>
     */
    #[ORM\OneToMany(targetEntity: EdgeRunnerDownTime::class, mappedBy: 'downtime', cascade: ['remove'])]
    private Collection $edgeRunnerDownTimes;

    public function __construct()
    {
        $this->edgeRunnerDownTimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getEffet(): ?string
    {
        return $this->effet;
    }

    public function setEffet(string $effet): static
    {
        $this->effet = $effet;

        return $this;
    }

    public function isForced(): bool
    {
        return $this->forced;
    }

    public function setForced(bool $forced): static
    {
        $this->forced = $forced;

        return $this;
    }

    public function getTimeCost(): ?int
    {
        return $this->timeCost;
    }

    public function setTimeCost(int $timeCost): static
    {
        $this->timeCost = $timeCost;

        return $this;
    }

    /**
     * @return Collection<int, EdgeRunnerDownTime>
     */
    public function getEdgeRunnerDownTimes(): Collection
    {
        return $this->edgeRunnerDownTimes;
    }

    public function addEdgeRunnerDownTime(EdgeRunnerDownTime $edgeRunnerDownTime): static
    {
        if (!$this->edgeRunnerDownTimes->contains($edgeRunnerDownTime)) {
            $this->edgeRunnerDownTimes->add($edgeRunnerDownTime);
            $edgeRunnerDownTime->setDowntime($this);
        }

        return $this;
    }

    public function removeEdgeRunnerDownTime(EdgeRunnerDownTime $edgeRunnerDownTime): static
    {
        if ($this->edgeRunnerDownTimes->removeElement($edgeRunnerDownTime)) {
            // set the owning side to null (unless already changed)
            if ($edgeRunnerDownTime->getDowntime() === $this) {
                $edgeRunnerDownTime->setDowntime(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? 'Downtime';
    }
}
