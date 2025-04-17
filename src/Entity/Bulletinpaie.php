<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BulletinpaieRepository;

#[ORM\Entity(repositoryClass: BulletinpaieRepository::class)]
#[ORM\Table(name: 'bulletinpaie')]
class Bulletinpaie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $employe_id = null;

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

    // Getter and Setter for employe_id
    public function getEmployeId(): ?int
    {
        return $this->employe_id;
    }

    public function setEmployeId(int $employe_id): self
    {
        $this->employe_id = $employe_id;
        return $this;
    }

    // Getter and Setter for mois
    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;
        return $this;
    }

    // Getter and Setter for annee
    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    // Getter and Setter for dateGeneration
    public function getDateGeneration(): ?\DateTimeInterface
    {
        return $this->dateGeneration;
    }

    public function setDateGeneration(\DateTimeInterface $dateGeneration): self
    {
        $this->dateGeneration = $dateGeneration;
        return $this;
    }

    // Getter and Setter for salaireBrut
    public function getSalaireBrut(): ?float
    {
        return $this->salaireBrut;
    }

    public function setSalaireBrut(float $salaireBrut): self
    {
        $this->salaireBrut = $salaireBrut;
        return $this;
    }

    // Getter and Setter for deductions
    public function getDeductions(): ?float
    {
        return $this->deductions;
    }

    public function setDeductions(float $deductions): self
    {
        $this->deductions = $deductions;
        return $this;
    }

    // Getter and Setter for salaireNet
    public function getSalaireNet(): ?float
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(float $salaireNet): self
    {
        $this->salaireNet = $salaireNet;
        return $this;
    }

    // Getter for id (if needed for querying or referencing the object)
    public function getId(): ?int
    {
        return $this->id;
    }
}
