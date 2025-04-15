<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PenaliteRepository;

#[ORM\Entity(repositoryClass: PenaliteRepository::class)]
#[ORM\Table(name: 'penalite')]
class Penalite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_pen = null;

    public function getID_pen(): ?int
    {
        return $this->ID_pen;
    }

    public function setID_pen(int $ID_pen): self
    {
        $this->ID_pen = $ID_pen;
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $seuil_abs = null;

    public function getSeuil_abs(): ?int
    {
        return $this->seuil_abs;
    }

    public function setSeuil_abs(int $seuil_abs): self
    {
        $this->seuil_abs = $seuil_abs;
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

    public function getIDPen(): ?int
    {
        return $this->ID_pen;
    }

    public function getSeuilAbs(): ?int
    {
        return $this->seuil_abs;
    }

    public function setSeuilAbs(int $seuil_abs): static
    {
        $this->seuil_abs = $seuil_abs;

        return $this;
    }

}
