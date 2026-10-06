<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\Table(name: 'avis')]
#[ORM\UniqueConstraint(name: 'unique_avis_user_demande', fields: ['userId', 'demandeId'])]
#[ORM\Index(name: 'fk_avis_demande', fields: ['demandeId'])]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'user_id')]
    private ?int $userId = null;

    #[ORM\Column(name: 'demande_id')]
    private ?int $demandeId = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true, columnDefinition: 'text COLLATE utf8mb4_unicode_ci')]
    private ?string $commentaire = null;

    #[ORM\Column(name: 'date_avis', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateAvis = null;

    public function getId(): ?int { return $this->id; }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(int $userId): self { $this->userId = $userId; return $this; }

    public function getDemandeId(): ?int { return $this->demandeId; }
    public function setDemandeId(int $demandeId): self { $this->demandeId = $demandeId; return $this; }

    public function getNote(): ?int { return $this->note; }
    public function setNote(int $note): self { $this->note = $note; return $this; }

    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): self { $this->commentaire = $commentaire; return $this; }

    public function getDateAvis(): ?\DateTimeImmutable { return $this->dateAvis; }
    public function setDateAvis(\DateTimeImmutable $dateAvis): self { $this->dateAvis = $dateAvis; return $this; }
}
