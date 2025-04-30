<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\PretRepository;

#[ORM\Entity(repositoryClass: PretRepository::class)]
#[UniqueEntity(fields: ['cin'], message: 'Un prêt existe déjà pour ce CIN.')]
#[ORM\Table(name: 'pret')]
class Pret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_pret = null;

    #[ORM\Column(type: 'string', length: 20, unique: true)] // ✅ unicité au niveau base
    #[Assert\NotBlank(message: "❌Le CIN est obligatoire.")]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: "❌Le CIN doit comporter au moins {{ limit }} caractères.",
        maxMessage: "❌Le CIN ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $cin = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "❌Le montant du prêt est obligatoire.")]
    #[Assert\Range(
        min: 0,
        notInRangeMessage: "❌Le montant du prêt doit être supérieur à 0."
    )]
    private ?float $Montant_pret = null;

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "❌La date du prêt est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "❌La date du prêt doit être aujourd'hui ou dans le futur.")]
    private ?\DateTimeInterface $Date_pret = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "❌Le TMM est obligatoire.")]
    #[Assert\PositiveOrZero(message: "❌Le TMM ne peut pas être négatif.")]
    private ?float $TMM = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "❌Le taux est obligatoire.")]
    #[Assert\Positive(message: "❌Le taux doit être un nombre positif.")]
    private ?float $Taux = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: "❌Les revenus bruts sont obligatoires.")]
    #[Assert\Positive(message: "❌Les revenus bruts doivent être supérieurs à 0.")]
    private ?float $Revenus_bruts = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "❌L'âge de l'employé est obligatoire.")]
    #[Assert\GreaterThan(value: 17, message: "❌L'âge de l'employé doit être supérieur à 18 ans.")]
    private ?int $Age_employe = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "❌La durée de remboursement est obligatoire.")]
    #[Assert\Range(
        min: 1,
        max: 30,
        notInRangeMessage: "❌La durée de remboursement doit être comprise entre 1 et 30 ans."
    )]
    private ?int $duree_remboursement = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank(message: "❌La catégorie est obligatoire.")]
    #[Assert\Choice(
        choices: ['Cadre', 'Employé', 'Ouvrier'],
        message: "❌Choisissez une catégorie valide."
    )] 
    private ?string $Categorie = null;

    // Getters et setters

    public function getID_pret(): ?int
    {
        return $this->ID_pret;
    }

    public function setID_pret(int $ID_pret): self
    {
        $this->ID_pret = $ID_pret;
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

    public function getMontantPret(): ?float
    {
        return $this->Montant_pret;
    }

    public function setMontantPret(?float $Montant_pret): self
    {
        $this->Montant_pret = $Montant_pret;
        return $this;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->Date_pret;
    }

    public function setDatePret(?\DateTimeInterface $Date_pret): self
    {
        $this->Date_pret = $Date_pret;
        return $this;
    }

    public function getTMM(): ?float
    {
        return $this->TMM;
    }

    public function setTMM(?float $TMM): self
    {
        $this->TMM = $TMM;
        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->Taux;
    }

    public function setTaux(?float $Taux): self
    {
        $this->Taux = $Taux;
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

    public function getAgeEmploye(): ?int
    {
        return $this->Age_employe;
    }

    public function setAgeEmploye(?int $Age_employe): self
    {
        $this->Age_employe = $Age_employe;
        return $this;
    }

    public function getDureeRemboursement(): ?int
    {
        return $this->duree_remboursement;
    }

    public function setDureeRemboursement(?int $duree_remboursement): self
    {
        $this->duree_remboursement = $duree_remboursement;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(?string $Categorie): self
    {
        $this->Categorie = $Categorie;
        return $this;
    }

  public function __toString(): string
    {
        return (string) $this->cin;
    }
}
