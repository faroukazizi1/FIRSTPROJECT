<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PretRepository;

#[ORM\Entity(repositoryClass: PretRepository::class)]
#[ORM\Table(name: 'pret')]
class Pret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_pret = null;

    public function getID_pret(): ?int
    {
        return $this->ID_pret;
    }

    public function setID_pret(int $ID_pret): self
    {
        $this->ID_pret = $ID_pret;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Montant_pret = null;

    public function getMontant_pret(): ?float
    {
        return $this->Montant_pret;
    }

    public function setMontant_pret(float $Montant_pret): self
    {
        $this->Montant_pret = $Montant_pret;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date_pret = null;

    public function getDate_pret(): ?\DateTimeInterface
    {
        return $this->Date_pret;
    }

    public function setDate_pret(\DateTimeInterface $Date_pret): self
    {
        $this->Date_pret = $Date_pret;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $TMM = null;

    public function getTMM(): ?float
    {
        return $this->TMM;
    }

    public function setTMM(float $TMM): self
    {
        $this->TMM = $TMM;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Taux = null;

    public function getTaux(): ?float
    {
        return $this->Taux;
    }

    public function setTaux(float $Taux): self
    {
        $this->Taux = $Taux;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Revenus_bruts = null;

    public function getRevenus_bruts(): ?float
    {
        return $this->Revenus_bruts;
    }

    public function setRevenus_bruts(float $Revenus_bruts): self
    {
        $this->Revenus_bruts = $Revenus_bruts;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $Age_employe = null;

    public function getAge_employe(): ?int
    {
        return $this->Age_employe;
    }

    public function setAge_employe(int $Age_employe): self
    {
        $this->Age_employe = $Age_employe;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $duree_remboursement = null;

    public function getDuree_remboursement(): ?int
    {
        return $this->duree_remboursement;
    }

    public function setDuree_remboursement(int $duree_remboursement): self
    {
        $this->duree_remboursement = $duree_remboursement;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Categorie = null;

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): self
    {
        $this->Categorie = $Categorie;
        return $this;
    }

    public function getIDPret(): ?int
    {
        return $this->ID_pret;
    }

    public function getMontantPret(): ?float
    {
        return $this->Montant_pret;
    }

    public function setMontantPret(float $Montant_pret): static
    {
        $this->Montant_pret = $Montant_pret;

        return $this;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->Date_pret;
    }

    public function setDatePret(\DateTimeInterface $Date_pret): static
    {
        $this->Date_pret = $Date_pret;

        return $this;
    }

    public function getRevenusBruts(): ?float
    {
        return $this->Revenus_bruts;
    }

    public function setRevenusBruts(float $Revenus_bruts): static
    {
        $this->Revenus_bruts = $Revenus_bruts;

        return $this;
    }

    public function getAgeEmploye(): ?int
    {
        return $this->Age_employe;
    }

    public function setAgeEmploye(int $Age_employe): static
    {
        $this->Age_employe = $Age_employe;

        return $this;
    }

    public function getDureeRemboursement(): ?int
    {
        return $this->duree_remboursement;
    }

    public function setDureeRemboursement(int $duree_remboursement): static
    {
        $this->duree_remboursement = $duree_remboursement;

        return $this;
    }

}
