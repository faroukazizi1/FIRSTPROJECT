<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BulletinpaieRepository;
use App\Entity\User;

#[ORM\Entity(repositoryClass: BulletinpaieRepository::class)]
#[ORM\Table(name: 'bulletinpaie')]
class Bulletinpaie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // 🔹 Relation avec l'entité User (employé)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $employe = null;

    #[ORM\Column(type: 'string')]
    private ?string $mois = null;

    #[ORM\Column(type: 'integer')]
    private ?int $annee = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateGeneration = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?float $salaireBrut = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?float $deductions = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?float $salaireNet = null;

    // ✅ Getters & Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?User
    {
        return $this->employe;
    }

    public function setEmploye(?User $employe): self
    {
        $this->employe = $employe;
        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;
        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    public function getDateGeneration(): ?\DateTimeInterface
    {
        return $this->dateGeneration;
    }

    public function setDateGeneration(\DateTimeInterface $dateGeneration): self
    {
        $this->dateGeneration = $dateGeneration;
        return $this;
    }

    public function getSalaireBrut(): ?float
    {
        return $this->salaireBrut;
    }

    public function setSalaireBrut(float $salaireBrut): self
    {
        $this->salaireBrut = $salaireBrut;
        return $this;
    }

    public function getDeductions(): ?float
    {
        return $this->deductions;
    }

    public function setDeductions(float $deductions): self
    {
        $this->deductions = $deductions;
        return $this;
    }

    public function getSalaireNet(): ?float
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(float $salaireNet): self
    {
        $this->salaireNet = $salaireNet;
        return $this;
    }
}
