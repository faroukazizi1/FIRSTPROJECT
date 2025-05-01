<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PromotionRepository;
use Endroid\QrCode\Builder\BuilderInterface;


#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ORM\Table(name: 'promotion')]
class Promotion
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_promo = null;

    public function getType_promo(): ?string
    {
        return $this->type_promo;
    }

    public function setType_promo(string $type_promo): self
    {
        $this->type_promo = $type_promo;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $raison = null;

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $poste_promo = null;

    public function getPoste_promo(): ?string
    {
        return $this->poste_promo;
    }

    public function setPoste_promo(string $poste_promo): self
    {
        $this->poste_promo = $poste_promo;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_promo = null;

    public function getDate_promo(): ?\DateTimeInterface
    {
        return $this->date_promo;
    }

    public function setDate_promo(?\DateTimeInterface $date_promo): self
    {
        $this->date_promo = $date_promo;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]

    private ?float $nouv_sal = null;

    public function getNouv_sal(): ?float
    {
        return $this->nouv_sal;
    }

    public function setNouv_sal(float $nouv_sal): self
    {
        $this->nouv_sal = $nouv_sal;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $avantage_supp = null;

    public function getAvantage_supp(): ?string
    {
        return $this->avantage_supp;
    }

    public function setAvantage_supp(string $avantage_supp): self
    {
        $this->avantage_supp = $avantage_supp;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'promotions')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getTypePromo(): ?string
    {
        return $this->type_promo;
    }

    public function setTypePromo(string $type_promo): static
    {
        $this->type_promo = $type_promo;

        return $this;
    }

    public function getPostePromo(): ?string
    {
        return $this->poste_promo;
    }

    public function setPostePromo(string $poste_promo): static
    {
        $this->poste_promo = $poste_promo;

        return $this;
    }

    public function getDatePromo(): ?\DateTimeInterface
    {
        return $this->date_promo;
    }

    public function setDatePromo(?\DateTimeInterface $date_promo): static
    {
        $this->date_promo = $date_promo;

        return $this;
    }

    public function getNouvSal(): ?string
    {
        return $this->nouv_sal;
    }

    public function setNouvSal(string $nouv_sal): static
    {
        $this->nouv_sal = $nouv_sal;

        return $this;
    }

    public function getAvantageSupp(): ?string
    {
        return $this->avantage_supp;
    }

    public function setAvantageSupp(string $avantage_supp): static
    {
        $this->avantage_supp = $avantage_supp;

        return $this;
    }
}
