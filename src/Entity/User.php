<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UserRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]

class User implements UserInterface , PasswordAuthenticatedUserInterface , TwoFactorInterface

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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false , unique: true)]

    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $username = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $sexe = null;

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $numero = null;

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $googleAuthenticatorSecret;


    #[ORM\OneToMany(targetEntity: Promotion::class, mappedBy: 'user')]
    private Collection $promotions;

  

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->projectTasks = new ArrayCollection();
    }
    


    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        if (!$this->promotions instanceof Collection) {
            $this->promotions = new ArrayCollection();
        }
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->getPromotions()->contains($promotion)) {
            $this->getPromotions()->add($promotion);
        }
        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        $this->getPromotions()->removeElement($promotion);
        return $this;
    }

    public function getRoles(): array
    {   
        $role = [];
        if($this->role == 'Employe'){
            $role[] = 'ROLE_Employe';
        }
        else if($this->role == 'RHR'){
            $role[] = 'RHR';
        }

        return array_unique($role);
    }

    public function eraseCredentials() : void
    {
        
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProjectTask::class, orphanRemoval: true)]
    private Collection $projectTasks;

 

    /**
     * @return Collection<int, ProjectTask>
     */
    public function getProjectTasks(): Collection
    {
        if (!$this->projectTasks instanceof Collection) {
            $this->projectTasks = new ArrayCollection();
        }
        return $this->projectTasks;
    }

    public function addProjectTask(ProjectTask $projectTask): self
    {
        if (!$this->getProjectTasks()->contains($projectTask)) {
            $this->getProjectTasks()->add($projectTask);
            $projectTask->setUser($this);
        }
        return $this;
    }

    public function removeProjectTask(ProjectTask $projectTask): self
    {
        if ($this->projectTasks->removeElement($projectTask)) {
            // set the owning side to null (unless already changed)
            if ($projectTask->getUser() === $this) {
                $projectTask->setUser(null);
            }
        }
        return $this;
    }


   /////2FA

   public function isGoogleAuthenticatorEnabled(): bool
   {
       return null !== $this->googleAuthenticatorSecret;
   }

   public function getGoogleAuthenticatorUsername(): string
   {
       return $this->username;
   }

   public function getGoogleAuthenticatorSecret(): ?string
   {
       return $this->googleAuthenticatorSecret;
   }

   public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
   {
       $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
   }

   

  


}
