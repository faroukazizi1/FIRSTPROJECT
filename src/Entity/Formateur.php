<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]

class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_Formateur', type: 'integer')]
    private ?int $idFormateur = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $nomF = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $prenomF = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $numero = null; // Ajout du champ numéro

    #[ORM\Column(type: 'string', length: 150)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $specialite = null;

    #[ORM\OneToMany(mappedBy: 'formateur', targetEntity: Formation::class)]

    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getIdFormateur(): ?int
    {
        return $this->idFormateur;
    }
    public function setIdFormateur(int $idFormateur): self
    {
        $this->idFormateur = $idFormateur;
        return $this;
    }


    public function getNomF(): ?string
    {
        return $this->nomF;
    }

    public function setNomF(?string $nomF): self
    {
        $this->nomF = $nomF;
        return $this;
    }

    public function getPrenomF(): ?string
    {
        return $this->prenomF;
    }

    public function setPrenomF(?string $prenomF): self
    {
        $this->prenomF = $prenomF;
        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }


    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {

        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setFormateur($this);
        }


        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            if ($formation->getFormateur() === $this) {
                $formation->setFormateur(null);
            }
        }


        return $this;
    }

    public function __toString(): string
    {
        return $this->nomF . ' ' . $this->prenomF;
    }

}
