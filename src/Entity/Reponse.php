<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReponseRepository;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
#[ORM\Table(name: 'reponse')]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_reponse = null;

    public function getID_reponse(): ?int
    {
        return $this->ID_reponse;
    }

    public function setID_reponse(int $ID_reponse): self
    {
        $this->ID_reponse = $ID_reponse;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date_reponse = null;

    public function getDate_reponse(): ?\DateTimeInterface
    {
        return $this->Date_reponse;
    }

    public function setDate_reponse(\DateTimeInterface $Date_reponse): self
    {
        $this->Date_reponse = $Date_reponse;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Montant_demande = null;

    public function getMontant_demande(): ?float
    {
        return $this->Montant_demande;
    }

    public function setMontant_demande(float $Montant_demande): self
    {
        $this->Montant_demande = $Montant_demande;
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

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Taux_interet = null;

    public function getTaux_interet(): ?float
    {
        return $this->Taux_interet;
    }

    public function setTaux_interet(float $Taux_interet): self
    {
        $this->Taux_interet = $Taux_interet;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Mensualite_credit = null;

    public function getMensualite_credit(): ?float
    {
        return $this->Mensualite_credit;
    }

    public function setMensualite_credit(float $Mensualite_credit): self
    {
        $this->Mensualite_credit = $Mensualite_credit;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Potentiel_credit = null;

    public function getPotentiel_credit(): ?float
    {
        return $this->Potentiel_credit;
    }

    public function setPotentiel_credit(float $Potentiel_credit): self
    {
        $this->Potentiel_credit = $Potentiel_credit;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $Duree_remboursement = null;

    public function getDuree_remboursement(): ?int
    {
        return $this->Duree_remboursement;
    }

    public function setDuree_remboursement(int $Duree_remboursement): self
    {
        $this->Duree_remboursement = $Duree_remboursement;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Montant_autorise = null;

    public function getMontant_autorise(): ?float
    {
        return $this->Montant_autorise;
    }

    public function setMontant_autorise(float $Montant_autorise): self
    {
        $this->Montant_autorise = $Montant_autorise;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Assurance = null;

    public function getAssurance(): ?float
    {
        return $this->Assurance;
    }

    public function setAssurance(float $Assurance): self
    {
        $this->Assurance = $Assurance;
        return $this;
    }

    public function getIDReponse(): ?int
    {
        return $this->ID_reponse;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->Date_reponse;
    }

    public function setDateReponse(\DateTimeInterface $Date_reponse): static
    {
        $this->Date_reponse = $Date_reponse;

        return $this;
    }

    public function getMontantDemande(): ?float
    {
        return $this->Montant_demande;
    }

    public function setMontantDemande(float $Montant_demande): static
    {
        $this->Montant_demande = $Montant_demande;

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

    public function getTauxInteret(): ?float
    {
        return $this->Taux_interet;
    }

    public function setTauxInteret(float $Taux_interet): static
    {
        $this->Taux_interet = $Taux_interet;

        return $this;
    }

    public function getMensualiteCredit(): ?float
    {
        return $this->Mensualite_credit;
    }

    public function setMensualiteCredit(float $Mensualite_credit): static
    {
        $this->Mensualite_credit = $Mensualite_credit;

        return $this;
    }

    public function getPotentielCredit(): ?float
    {
        return $this->Potentiel_credit;
    }

    public function setPotentielCredit(float $Potentiel_credit): static
    {
        $this->Potentiel_credit = $Potentiel_credit;

        return $this;
    }

    public function getDureeRemboursement(): ?int
    {
        return $this->Duree_remboursement;
    }

    public function setDureeRemboursement(int $Duree_remboursement): static
    {
        $this->Duree_remboursement = $Duree_remboursement;

        return $this;
    }

    public function getMontantAutorise(): ?float
    {
        return $this->Montant_autorise;
    }

    public function setMontantAutorise(float $Montant_autorise): static
    {
        $this->Montant_autorise = $Montant_autorise;

        return $this;
    }

}
