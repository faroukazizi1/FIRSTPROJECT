<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProjectRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'project')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToMany(
        mappedBy: "project", 
        targetEntity: ProjectTask::class,
        cascade: ["persist", "remove"], // Add "remove" for cascade deletion
        orphanRemoval: true
    )]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
    #[Assert\NotBlank(message: 'Le titre ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le titre doit contenir au moins 3 caractères', maxMessage: 'Le titre doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'Le titre doit être une chaîne de caractères')]
    private ?string $titre = null;

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)] // Changed to text for longer descriptions
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'La description doit contenir au moins 3 caractères', maxMessage: 'La description doit contenir au maximum 255 caractères')]
    #[Assert\Type('string', message: 'La description doit être une chaîne de caractères')]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le statut ne doit pas être vide')]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotNull(message: 'La date de début ne doit pas être vide')]
    #[Assert\LessThanOrEqual(propertyPath: 'dateFin', message: 'La date de début doit être inférieure à la date de fin')]
    private ?\DateTimeInterface $date_debut = null;
    

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }
    
    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotNull(message: 'La date de fin ne doit pas être vide')]
    #[Assert\GreaterThanOrEqual(propertyPath: 'dateDebut', message: 'La date de fin doit être supérieure à la date de début')]
    private ?\DateTimeInterface $date_fin = null;

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }
    

    /**
     * @return Collection<int, ProjectTask>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(ProjectTask $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }
        return $this;
    }

    public function removeTask(ProjectTask $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }
        return $this;
    }
}