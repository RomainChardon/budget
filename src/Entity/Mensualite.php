<?php

namespace App\Entity;

use App\Repository\MensualiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MensualiteRepository::class)]
class Mensualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'mensualite')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: Revenu::class, mappedBy: 'mensualite')]
    private Collection $revenues;

    #[ORM\ManyToOne(inversedBy: 'mensualites')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $actif = null;

    #[ORM\Column]
    private ?float $soldeDepart = null;

    #[ORM\OneToMany(targetEntity: Prevues::class, mappedBy: 'mensualite')]
    private Collection $prevues;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
        $this->revenues = new ArrayCollection();
        $this->prevues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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
            $depense->setMensualite($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getMensualite() === $this) {
                $depense->setMensualite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Revenu>
     */
    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function addRevenue(Revenu $revenue): static
    {
        if (!$this->revenues->contains($revenue)) {
            $this->revenues->add($revenue);
            $revenue->setMensualite($this);
        }

        return $this;
    }

    public function removeRevenue(Revenu $revenue): static
    {
        if ($this->revenues->removeElement($revenue)) {
            // set the owning side to null (unless already changed)
            if ($revenue->getMensualite() === $this) {
                $revenue->setMensualite(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getActif(): ?int
    {
        return $this->actif;
    }

    public function setActif(?int $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getSoldeDepart(): ?float
    {
        return $this->soldeDepart;
    }

    public function setSoldeDepart(float $soldeDepart): static
    {
        $this->soldeDepart = $soldeDepart;

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
            $prevue->setMensualite($this);
        }

        return $this;
    }

    public function removePrevue(Prevues $prevue): static
    {
        if ($this->prevues->removeElement($prevue)) {
            // set the owning side to null (unless already changed)
            if ($prevue->getMensualite() === $this) {
                $prevue->setMensualite(null);
            }
        }

        return $this;
    }
}
