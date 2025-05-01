<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BulletinpaieRepository;

#[ORM\Entity(repositoryClass: BulletinpaieRepository::class)]
#[ORM\Table(name: 'bulletinpaie')]
class Bulletinpaie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $employe_id = null;

    public function getEmploye_id(): ?int
    {
        return $this->employe_id;
    }

    public function setEmploye_id(int $employe_id): self
    {
        $this->employe_id = $employe_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $mois = null;

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $annee = null;

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $salaire_brut = null;


    public function getSalaire_brut(): ?float
    {
        return $this->salaire_brut;
    }

    public function setSalaire_brut(float $salaire_brut): self
    {
        $this->salaire_brut = $salaire_brut;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $deductions = null;


    public function getDeductions(): ?float
    {
        return $this->deductions;
    }

    public function setDeductions(float $deductions): self
    {
        $this->deductions = $deductions;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $salaire_net = null;


    public function getSalaire_net(): ?float
    {
        return $this->salaire_net;
    }

    public function setSalaire_net(float $salaire_net): self
    {
        $this->salaire_net = $salaire_net;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_generation = null;

    public function getDate_generation(): ?\DateTimeInterface
    {
        return $this->date_generation;
    }

    public function setDate_generation(\DateTimeInterface $date_generation): self
    {
        $this->date_generation = $date_generation;
        return $this;
    }

    public function getEmployeId(): ?int
    {
        return $this->employe_id;
    }

    public function setEmployeId(int $employe_id): static
    {
        $this->employe_id = $employe_id;

        return $this;
    }

    public function getSalaireBrut(): ?string
    {
        return $this->salaire_brut;
    }

    public function setSalaireBrut(string $salaire_brut): static
    {
        $this->salaire_brut = $salaire_brut;

        return $this;
    }

    public function getSalaireNet(): ?string
    {
        return $this->salaire_net;
    }

    public function setSalaireNet(string $salaire_net): static
    {
        $this->salaire_net = $salaire_net;

        return $this;
    }

    public function getDateGeneration(): ?\DateTimeInterface
    {
        return $this->date_generation;
    }

    public function setDateGeneration(\DateTimeInterface $date_generation): static
    {
        $this->date_generation = $date_generation;

        return $this;
    }

}
