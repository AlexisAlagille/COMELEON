<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\ManyToMany(targetEntity: Demande::class, mappedBy: 'prestations')]
    private Collection $demandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): self { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(int $duree): self { $this->duree = $duree; return $this; }

    public function getPrix(): ?float { return $this->prix; }
    public function setPrix(float $prix): self { $this->prix = $prix; return $this; }

    public function getCategorie(): ?string { return $this->categorie; }
    public function setCategorie(string $categorie): self { $this->categorie = $categorie; return $this; }

    public function getImage(): ?string{ return $this->image; }
    
    public function setImage(?string $image): static{ $this->image = $image; return $this; }

    public function getDemandes(): Collection { return $this->demandes; }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->addPrestation($this);
        }
        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            $demande->removePrestation($this);
        }
        return $this;
    }
}
