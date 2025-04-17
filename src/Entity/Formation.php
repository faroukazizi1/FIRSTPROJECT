<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Formateur;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: 'formation')]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_form = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $Titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $Description = null;

    #[ORM\Column(type: 'date', nullable: true)]
private ?\DateTimeInterface $dateD = null;

#[ORM\Column(type: 'date', nullable: true)]
private ?\DateTimeInterface $dateF = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $Duree = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $Image = null;

    #[ORM\ManyToOne(targetEntity: Formateur::class, inversedBy: 'formations')]
    #[ORM\JoinColumn(name: 'id_Formateur', referencedColumnName: 'id_Formateur')]
    private ?Formateur $formateur = null;

    public function getId_form(): ?int
    {
        return $this->id_form;
    }

    public function setId_form(int $id_form): self
    {
        $this->id_form = $id_form;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): self
    {
        $this->Titre = $Titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->dateD;
    }
    
    public function setDateD(?\DateTimeInterface $dateD): self
    {
        $this->dateD = $dateD;
        return $this;
    }
    
    public function getDateF(): ?\DateTimeInterface
    {
        return $this->dateF;
    }
    
    public function setDateF(?\DateTimeInterface $dateF): self
    {
        $this->dateF = $dateF;
        return $this;
    }
    public function getDuree(): ?int
    {
        return $this->Duree;
    }

    public function setDuree(?int $Duree): self
    {
        $this->Duree = $Duree;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;
        return $this;
    }

    // Méthodes pour la relation avec Formateur
    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        if ($this->formateur && $this->formateur !== $formateur) {
            $this->formateur->removeFormation($this);
        }

        $this->formateur = $formateur;

        if ($formateur) {
            $formateur->addFormation($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->Titre ?? 'Nouvelle formation'; // Correction du nom de la propriété
    }
    #[Assert\Callback]
public function validateDates(ExecutionContextInterface $context): void
{
    $today = new \DateTime('today');

    if ($this->dateD && $this->dateD < $today) {
        $context->buildViolation('La date de début ne peut pas être dans le passé.')
            ->atPath('dateD')
            ->addViolation();
    }

    if ($this->dateD && $this->dateF && $this->dateF < $this->dateD) {
        $context->buildViolation('La date de fin doit être postérieure à la date de début.')
            ->atPath('dateF')
            ->addViolation();
    }
}

}
