<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\Table(name: 'avis')]
#[ORM\UniqueConstraint(name: 'unique_avis_user_demande', fields: ['user', 'demande'])]
#[ORM\Index(name: 'fk_avis_demande', fields: ['demande'])]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Demande::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(name: 'demande_id', nullable: false)]
    private ?Demande $demande = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true, columnDefinition: 'text COLLATE utf8mb4_unicode_ci')]
    private ?string $commentaire = null;

    #[ORM\Column(name: 'date_avis', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateAvis = null;

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getDemande(): ?Demande
    {
        return $this->demande;
    }

    public function setDemande(?Demande $demande): self
    {
        $this->demande = $demande;

        return $this;
    }

    public function getNote(): ?int { return $this->note; }
    public function setNote(int $note): self { $this->note = $note; return $this; }

    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): self { $this->commentaire = $commentaire; return $this; }

    public function getDateAvis(): ?\DateTimeImmutable { return $this->dateAvis; }
    public function setDateAvis(\DateTimeImmutable $dateAvis): self { $this->dateAvis = $dateAvis; return $this; }
}
