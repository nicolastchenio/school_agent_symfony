<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(length: 100)]
    private ?string $niveauEducation = null;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'user')]
    private Collection $agent;

    public function __construct()
    {
        $this->agent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getNiveauEducation(): ?string
    {
        return $this->niveauEducation;
    }

    public function setNiveauEducation(string $niveauEducation): static
    {
        $this->niveauEducation = $niveauEducation;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getAgent(): Collection
    {
        return $this->agent;
    }

    public function addAgent(Conversation $agent): static
    {
        if (!$this->agent->contains($agent)) {
            $this->agent->add($agent);
            $agent->setUser($this);
        }

        return $this;
    }

    public function removeAgent(Conversation $agent): static
    {
        if ($this->agent->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getUser() === $this) {
                $agent->setUser(null);
            }
        }

        return $this;
    }
}
