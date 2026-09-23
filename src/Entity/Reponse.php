<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_reponse = null;

    #[ORM\OneToOne(inversedBy: 'reponse')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demande $demande = null;

    public function getId(): ?int { return $this->id; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): self { $this->contenu = $contenu; return $this; }

    public function getDateReponse(): ?\DateTimeImmutable { return $this->date_reponse; }
    public function setDateReponse(\DateTimeImmutable $date): self { $this->date_reponse = $date; return $this; }

    public function getDemande(): ?Demande { return $this->demande; }
    public function setDemande(?Demande $demande): self { $this->demande = $demande; return $this; }
}
