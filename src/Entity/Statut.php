<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idStatut = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleStatut = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Demande::class)]
    private Collection $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getIdStatut(): ?int { return $this->idStatut; }

    public function getLibelleStatut(): ?string { return $this->libelleStatut; }
    public function setLibelleStatut(string $libelleStatut): self { $this->libelleStatut = $libelleStatut; return $this; }

    public function getDemandes(): Collection { return $this->demandes; }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setStatut($this);
        }
        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            if ($demande->getStatut() === $this) {
                $demande->setStatut(null);
            }
        }
        return $this;
    }
}
