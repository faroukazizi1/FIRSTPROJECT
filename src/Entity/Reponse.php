<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReponseRepository::class)]class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_reponse = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le CIN est obligatoire.")]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: "Le CIN doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le CIN ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $cin = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\NotNull(message: "La date de réponse est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date de réponse doit être aujourd'hui ou dans le futur.")]
    private ?\DateTimeInterface $Date_reponse = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "Le montant demandé est obligatoire.")]
    #[Assert\Positive(message: "Le montant demandé doit être positif.")]
    private ?float $Montant_demande = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "Les revenus bruts sont obligatoires.")]
    #[Assert\Positive(message: "Les revenus bruts doivent être positifs.")]
    private ?float $Revenus_bruts = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "Le taux d'intérêt est obligatoire.")]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: "Le taux d'intérêt doit être entre {{ min }}% et {{ max }}%."
    )]
    private ?float $Taux_interet = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "La mensualité est obligatoire.")]
    #[Assert\PositiveOrZero(message: "La mensualité ne peut pas être négative.")]
    private ?float $Mensualite_credit = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "Le potentiel de crédit est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le potentiel de crédit ne peut pas être négatif.")]
    private ?float $Potentiel_credit = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\NotNull(message: "La durée de remboursement est obligatoire.")]
    #[Assert\Range(
        min: 1,
        max: 30,
        notInRangeMessage: "La durée de remboursement doit être comprise entre {{ min }} et {{ max }} ans."
    )]
    private ?int $Duree_remboursement = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "Le montant autorisé est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le montant autorisé ne peut pas être négatif.")]
    private ?float $Montant_autorise = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\NotNull(message: "L'assurance est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le montant de l'assurance ne peut pas être négatif.")]
    private ?float $Assurance = null;

    // === GETTERS & SETTERS ===

    public function getID_reponse(): ?int
    {
        return $this->ID_reponse;
    }

    public function setID_reponse(int $ID_reponse): self
    {
        $this->ID_reponse = $ID_reponse;
        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->Date_reponse;
    }

    public function setDateReponse(?\DateTimeInterface $Date_reponse): self
    {
        $this->Date_reponse = $Date_reponse;
        return $this;
    }

    public function getMontantDemande(): ?float
    {
        return $this->Montant_demande;
    }

    public function setMontantDemande(?float $Montant_demande): self
    {
        $this->Montant_demande = $Montant_demande;
        return $this;
    }

    public function getRevenusBruts(): ?float
    {
        return $this->Revenus_bruts;
    }

    public function setRevenusBruts(?float $Revenus_bruts): self
    {
        $this->Revenus_bruts = $Revenus_bruts;
        return $this;
    }

    public function getTauxInteret(): ?float
    {
        return $this->Taux_interet;
    }

    public function setTauxInteret(?float $Taux_interet): self
    {
        $this->Taux_interet = $Taux_interet;
        return $this;
    }

    public function getMensualiteCredit(): ?float
    {
        return $this->Mensualite_credit;
    }

    public function setMensualiteCredit(?float $Mensualite_credit): self
    {
        $this->Mensualite_credit = $Mensualite_credit;
        return $this;
    }

    public function getPotentielCredit(): ?float
    {
        return $this->Potentiel_credit;
    }

    public function setPotentielCredit(?float $Potentiel_credit): self
    {
        $this->Potentiel_credit = $Potentiel_credit;
        return $this;
    }

    public function getDureeRemboursement(): ?int
    {
        return $this->Duree_remboursement;
    }

    public function setDureeRemboursement(?int $Duree_remboursement): self
    {
        $this->Duree_remboursement = $Duree_remboursement;
        return $this;
    }

    public function getMontantAutorise(): ?float
    {
        return $this->Montant_autorise;
    }

    public function setMontantAutorise(?float $Montant_autorise): self
    {
        $this->Montant_autorise = $Montant_autorise;
        return $this;
    }

    public function getAssurance(): ?float
    {
        return $this->Assurance;
    }

    public function setAssurance(?float $Assurance): self
    {
        $this->Assurance = $Assurance;
        return $this;
    }}
