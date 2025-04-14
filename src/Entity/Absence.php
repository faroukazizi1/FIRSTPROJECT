<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AbsenceRepository;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
#[ORM\Table(name: 'absence')]
class Absence
{
    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date = null;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nbr_abs = null;

    public function getNbr_abs(): ?int
    {
        return $this->nbr_abs;
    }

    public function setNbr_abs(int $nbr_abs): self
    {
        $this->nbr_abs = $nbr_abs;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_abs = null;

    public function getID_abs(): ?int
    {
        return $this->ID_abs;
    }

    public function setID_abs(int $ID_abs): self
    {
        $this->ID_abs = $ID_abs;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $cin = null;

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $image_path = null;

    public function getImage_path(): ?string
    {
        return $this->image_path;
    }

    public function setImage_path(?string $image_path): self
    {
        $this->image_path = $image_path;
        return $this;
    }

    public function getNbrAbs(): ?int
    {
        return $this->nbr_abs;
    }

    public function setNbrAbs(int $nbr_abs): static
    {
        $this->nbr_abs = $nbr_abs;

        return $this;
    }

    public function getIDAbs(): ?int
    {
        return $this->ID_abs;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): static
    {
        $this->image_path = $image_path;

        return $this;
    }

}
