<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestation $prestation = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(name: "statut_id", referencedColumnName: "idStatut", nullable: false)]

    private ?Statut $statut = null;

    #[ORM\OneToOne(mappedBy: 'demande', cascade: ['persist', 'remove'])]
    private ?Reponse $reponse = null;

    public function getId(): ?int { return $this->id; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDate(): ?\DateTimeImmutable { return $this->date; }
    public function setDate(\DateTimeImmutable $date): self { $this->date = $date; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getPrestation(): ?Prestation { return $this->prestation; }
    public function setPrestation(?Prestation $prestation): self { $this->prestation = $prestation; return $this; }

    public function getStatut(): ?Statut { return $this->statut; }
    public function setStatut(?Statut $statut): self { $this->statut = $statut; return $this; }

    public function getReponse(): ?Reponse { return $this->reponse; }
    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;
        if ($reponse && $reponse->getDemande() !== $this) {
            $reponse->setDemande($this);
        }
        return $this;
    }
}
