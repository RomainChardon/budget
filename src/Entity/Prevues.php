<?php

namespace App\Entity;

use App\Repository\PrevuesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevuesRepository::class)]
class Prevues
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prevues')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'prevues')]
    private ?Mensualite $mensualite = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'prevues')]
    private ?CategorieDepense $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'prevues')]
    private ?CategorieRevenu $categorieRevenues = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMensualite(): ?Mensualite
    {
        return $this->mensualite;
    }

    public function setMensualite(?Mensualite $mensualite): static
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCategorie(): ?CategorieDepense
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieDepense $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCategorieRevenues(): ?CategorieRevenu
    {
        return $this->categorieRevenues;
    }

    public function setCategorieRevenues(?CategorieRevenu $categorieRevenues): static
    {
        $this->categorieRevenues = $categorieRevenues;

        return $this;
    }
}
