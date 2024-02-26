<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

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

    #[ORM\OneToMany(targetEntity: CategorieDepense::class, mappedBy: 'User')]
    private Collection $categorieDepenses;

    #[ORM\OneToMany(targetEntity: CategorieRevenu::class, mappedBy: 'User')]
    private Collection $categorieRevenus;

    #[ORM\OneToMany(targetEntity: Mensualite::class, mappedBy: 'user')]
    private Collection $mensualites;

    #[ORM\OneToMany(targetEntity: Prevues::class, mappedBy: 'user')]
    private Collection $prevues;

    public function __construct()
    {
        $this->categorieDepenses = new ArrayCollection();
        $this->categorieRevenus = new ArrayCollection();
        $this->mensualites = new ArrayCollection();
        $this->prevues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, CategorieDepense>
     */
    public function getCategorieDepenses(): Collection
    {
        return $this->categorieDepenses;
    }

    public function addCategorieDepense(CategorieDepense $categorieDepense): static
    {
        if (!$this->categorieDepenses->contains($categorieDepense)) {
            $this->categorieDepenses->add($categorieDepense);
            $categorieDepense->setUser($this);
        }

        return $this;
    }

    public function removeCategorieDepense(CategorieDepense $categorieDepense): static
    {
        if ($this->categorieDepenses->removeElement($categorieDepense)) {
            // set the owning side to null (unless already changed)
            if ($categorieDepense->getUser() === $this) {
                $categorieDepense->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategorieRevenu>
     */
    public function getCategorieRevenus(): Collection
    {
        return $this->categorieRevenus;
    }

    public function addCategorieRevenu(CategorieRevenu $categorieRevenu): static
    {
        if (!$this->categorieRevenus->contains($categorieRevenu)) {
            $this->categorieRevenus->add($categorieRevenu);
            $categorieRevenu->setUser($this);
        }

        return $this;
    }

    public function removeCategorieRevenu(CategorieRevenu $categorieRevenu): static
    {
        if ($this->categorieRevenus->removeElement($categorieRevenu)) {
            // set the owning side to null (unless already changed)
            if ($categorieRevenu->getUser() === $this) {
                $categorieRevenu->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mensualite>
     */
    public function getMensualites(): Collection
    {
        return $this->mensualites;
    }

    public function addMensualite(Mensualite $mensualite): static
    {
        if (!$this->mensualites->contains($mensualite)) {
            $this->mensualites->add($mensualite);
            $mensualite->setUser($this);
        }

        return $this;
    }

    public function removeMensualite(Mensualite $mensualite): static
    {
        if ($this->mensualites->removeElement($mensualite)) {
            // set the owning side to null (unless already changed)
            if ($mensualite->getUser() === $this) {
                $mensualite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prevues>
     */
    public function getPrevues(): Collection
    {
        return $this->prevues;
    }

    public function addPrevue(Prevues $prevue): static
    {
        if (!$this->prevues->contains($prevue)) {
            $this->prevues->add($prevue);
            $prevue->setUser($this);
        }

        return $this;
    }

    public function removePrevue(Prevues $prevue): static
    {
        if ($this->prevues->removeElement($prevue)) {
            // set the owning side to null (unless already changed)
            if ($prevue->getUser() === $this) {
                $prevue->setUser(null);
            }
        }

        return $this;
    }
}
