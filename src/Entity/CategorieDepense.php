<?php

namespace App\Entity;

use App\Repository\CategorieDepenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieDepenseRepository::class)]
class CategorieDepense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'categorieDepenses')]
    private ?User $User = null;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'categorie')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: Prevues::class, mappedBy: 'categorie')]
    private Collection $prevues;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
        $this->prevues = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, Depense>
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): static
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses->add($depense);
            $depense->setCategorie($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getCategorie() === $this) {
                $depense->setCategorie(null);
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
            $prevue->setCategorie($this);
        }

        return $this;
    }

    public function removePrevue(Prevues $prevue): static
    {
        if ($this->prevues->removeElement($prevue)) {
            // set the owning side to null (unless already changed)
            if ($prevue->getCategorie() === $this) {
                $prevue->setCategorie(null);
            }
        }

        return $this;
    }
}
