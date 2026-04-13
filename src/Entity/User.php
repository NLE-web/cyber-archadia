<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Edgerunner>
     */
    #[ORM\OneToMany(targetEntity: Edgerunner::class, mappedBy: 'player')]
    private Collection $edgerunners;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $themeColor = null;

    public function __construct()
    {
        $this->edgerunners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'roles' => $this->roles,
            'password' => isset($this->password) ? hash('crc32c', $this->password) : null,
            'themeColor' => $this->themeColor,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->roles = $data['roles'] ?? [];
        $this->password = $data['password'] ?? null;
        $this->themeColor = $data['themeColor'] ?? null;
        $this->edgerunners = new ArrayCollection();
    }

    /**
     * @return Collection<int, Edgerunner>
     */
    public function getEdgerunners(): Collection
    {
        return $this->edgerunners;
    }

    public function addEdgerunner(Edgerunner $edgerunner): static
    {
        if (!$this->edgerunners->contains($edgerunner)) {
            $this->edgerunners->add($edgerunner);
            $edgerunner->setPlayer($this);
        }

        return $this;
    }

    public function removeEdgerunner(Edgerunner $edgerunner): static
    {
        if ($this->edgerunners->removeElement($edgerunner)) {
            // set the owning side to null (unless already changed)
            if ($edgerunner->getPlayer() === $this) {
                $edgerunner->setPlayer(null);
            }
        }

        return $this;
    }

    public function getThemeColor(): ?string
    {
        return $this->themeColor;
    }

    public function setThemeColor(?string $themeColor): static
    {
        $this->themeColor = $themeColor;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username ?? 'Utilisateur';
    }
}
