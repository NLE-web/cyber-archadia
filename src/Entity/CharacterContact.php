<?php

namespace App\Entity;

use App\Repository\CharacterContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterContactRepository::class)]
class CharacterContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'characterContacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Edgerunner $character = null;

    #[ORM\ManyToOne(inversedBy: 'characterContacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'characterContact', orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacter(): ?Edgerunner
    {
        return $this->character;
    }

    public function setCharacter(?Edgerunner $character): static
    {
        $this->character = $character;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setCharacterContact($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCharacterContact() === $this) {
                $message->setCharacterContact(null);
            }
        }

        return $this;
    }

    public function getUnreadCount(): int
    {
        $count = 0;
        foreach ($this->messages as $message) {
            if (!$message->isRead() && $message->isFromContact()) {
                $count++;
            }
        }
        return $count;
    }
}
