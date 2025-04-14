<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FormateurRepository;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
#[ORM\Table(name: 'formateur')]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: "id_Formateur")]
    private ?int $id_Formateur = null;

    public function getId_Formateur(): ?int
    {
        return $this->id_Formateur;
    }

    public function setId_Formateur(int $id_Formateur): self
    {
        $this->id_Formateur = $id_Formateur;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $Numero = null;

    public function getNumero(): ?int
    {
        return $this->Numero;
    }

    public function setNumero(int $Numero): self
    {
        $this->Numero = $Numero;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Nom_F = null;

    public function getNom_F(): ?string
    {
        return $this->Nom_F;
    }

    public function setNom_F(string $Nom_F): self
    {
        $this->Nom_F = $Nom_F;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Prenom_F = null;

    public function getPrenom_F(): ?string
    {
        return $this->Prenom_F;
    }

    public function setPrenom_F(string $Prenom_F): self
    {
        $this->Prenom_F = $Prenom_F;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Email = null;

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Specialite = null;

    public function getSpecialite(): ?string
    {
        return $this->Specialite;
    }

    public function setSpecialite(string $Specialite): self
    {
        $this->Specialite = $Specialite;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'formateur')]
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        if (!$this->formations instanceof Collection) {
            $this->formations = new ArrayCollection();
        }
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->getFormations()->contains($formation)) {
            $this->getFormations()->add($formation);
        }
        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        $this->getFormations()->removeElement($formation);
        return $this;
    }

    public function getIdFormateur(): ?int
    {
        return $this->id_Formateur;
    }

    public function getNomF(): ?string
    {
        return $this->Nom_F;
    }

    public function setNomF(string $Nom_F): static
    {
        $this->Nom_F = $Nom_F;

        return $this;
    }

    public function getPrenomF(): ?string
    {
        return $this->Prenom_F;
    }

    public function setPrenomF(string $Prenom_F): static
    {
        $this->Prenom_F = $Prenom_F;

        return $this;
    }

}
