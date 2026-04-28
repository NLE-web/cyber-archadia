<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?ImageFile $image = null;

    /**
     * @var Collection<int, CharacterContact>
     */
    #[ORM\OneToMany(targetEntity: CharacterContact::class, mappedBy: 'contact', orphanRemoval: true)]
    private Collection $characterContacts;

    public function __construct()
    {
        $this->characterContacts = new ArrayCollection();
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

    public function getImage(): ?ImageFile
    {
        return $this->image;
    }

    public function setImage(ImageFile|string|null $image): static
    {
        if (is_string($image)) {
            $imageFile = new ImageFile();
            $imageFile->setDisplayName($this->name . " Image");
            $imageFile->setStorageName($image);
            $this->image = $imageFile;
        } else {
            $this->image = $image;
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
            $characterContact->setContact($this);
        }

        return $this;
    }

    public function removeCharacterContact(CharacterContact $characterContact): static
    {
        if ($this->characterContacts->removeElement($characterContact)) {
            // set the owning side to null (unless already changed)
            if ($characterContact->getContact() === $this) {
                $characterContact->setContact(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
