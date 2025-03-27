<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FormationRepository;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: 'formation')]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_form = null;

    public function getId_form(): ?int
    {
        return $this->id_form;
    }

    public function setId_form(int $id_form): self
    {
        $this->id_form = $id_form;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $Titre = null;

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): self
    {
        $this->Titre = $Titre;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $Description = null;

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $Date_D = null;

    public function getDate_D(): ?\DateTimeInterface
    {
        return $this->Date_D;
    }

    public function setDate_D(?\DateTimeInterface $Date_D): self
    {
        $this->Date_D = $Date_D;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $Date_F = null;

    public function getDate_F(): ?\DateTimeInterface
    {
        return $this->Date_F;
    }

    public function setDate_F(?\DateTimeInterface $Date_F): self
    {
        $this->Date_F = $Date_F;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $Duree = null;

    public function getDuree(): ?int
    {
        return $this->Duree;
    }

    public function setDuree(?int $Duree): self
    {
        $this->Duree = $Duree;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $Image = null;

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Formateur::class, inversedBy: 'formations')]
    #[ORM\JoinColumn(name: 'id_Formateur', referencedColumnName: 'id_Formateur')]
    private ?Formateur $formateur = null;

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;
        return $this;
    }

    public function getIdForm(): ?int
    {
        return $this->id_form;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->Date_D;
    }

    public function setDateD(?\DateTimeInterface $Date_D): static
    {
        $this->Date_D = $Date_D;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->Date_F;
    }

    public function setDateF(?\DateTimeInterface $Date_F): static
    {
        $this->Date_F = $Date_F;

        return $this;
    }

}
