<?php

namespace App\Entity;

use App\Repository\CategorieRevenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRevenuRepository::class)]
class CategorieRevenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'categorieRevenus')]
    private ?User $User = null;

    #[ORM\OneToMany(targetEntity: Revenu::class, mappedBy: 'categorie')]
    private Collection $revenus;

    #[ORM\OneToMany(targetEntity: Prevues::class, mappedBy: 'categorieRevenues')]
    private Collection $prevues;

    public function __construct()
    {
        $this->revenus = new ArrayCollection();
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
     * @return Collection<int, Revenu>
     */
    public function getRevenus(): Collection
    {
        return $this->revenus;
    }

    public function addRevenu(Revenu $revenu): static
    {
        if (!$this->revenus->contains($revenu)) {
            $this->revenus->add($revenu);
            $revenu->setCategorie($this);
        }

        return $this;
    }

    public function removeRevenu(Revenu $revenu): static
    {
        if ($this->revenus->removeElement($revenu)) {
            // set the owning side to null (unless already changed)
            if ($revenu->getCategorie() === $this) {
                $revenu->setCategorie(null);
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
            $prevue->setCategorieRevenues($this);
        }

        return $this;
    }

    public function removePrevue(Prevues $prevue): static
    {
        if ($this->prevues->removeElement($prevue)) {
            // set the owning side to null (unless already changed)
            if ($prevue->getCategorieRevenues() === $this) {
                $prevue->setCategorieRevenues(null);
            }
        }

        return $this;
    }
}
