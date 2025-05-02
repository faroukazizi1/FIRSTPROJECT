<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandecongeRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandecongeRepository::class)]
#[ORM\Table(name: 'demande_conge')]
class Demandeconge
{
    public const TYPE_CONGE_MALADIE = 'MALADIE';
    public const TYPE_CONGE_MATERNITE = 'MATERNITE';
    public const TYPE_CONGE_SANS_SOLDE = 'SANS_SOLDE';
    public const TYPE_CONGE_ANNUELLE = 'ANNUELLE';
    public const TYPE_CONGE_AUTRE = 'AUTRE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $employeId = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $typeConge = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateGeneration = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 1, max: 12)]
    private ?int $mois = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 2000, max: 2100)]
    private ?int $annee = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $salaireBrut = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $deductions = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $salaireNet = null;

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeId(): ?int
    {
        return $this->employeId;
    }

    public function setEmployeId(int $employeId): self
    {
        $this->employeId = $employeId;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getTypeConge(): ?string
    {
        return $this->typeConge;
    }

    public function setTypeConge(?string $typeConge): self
    {
        $this->typeConge = $typeConge;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;
        return $this;
    }

    public function getDateGeneration(): ?\DateTimeInterface
    {
        return $this->dateGeneration;
    }

    public function setDateGeneration(?\DateTimeInterface $dateGeneration): self
    {
        $this->dateGeneration = $dateGeneration;
        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): self
    {
        $this->mois = $mois;
        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    public function getSalaireBrut(): ?int
    {
        return $this->salaireBrut;
    }

    public function setSalaireBrut(?int $salaireBrut): self
    {
        $this->salaireBrut = $salaireBrut;
        return $this;
    }

    public function getDeductions(): ?float
    {
        return $this->deductions;
    }

    public function setDeductions(?float $deductions): self
    {
        $this->deductions = $deductions;
        return $this;
    }

    public function getSalaireNet(): ?float
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(?float $salaireNet): self
    {
        $this->salaireNet = $salaireNet;
        return $this;
    }

    public static function getTypeCongeChoices(): array
    {
        return [
            'Maladie' => self::TYPE_CONGE_MALADIE,
            'Maternité' => self::TYPE_CONGE_MATERNITE,
            'Sans Solde' => self::TYPE_CONGE_SANS_SOLDE,
            'Annuelle' => self::TYPE_CONGE_ANNUELLE,
            'Autre' => self::TYPE_CONGE_AUTRE,
        ];
    }
}