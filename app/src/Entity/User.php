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

    #[ORM\ManyToOne(targetEntity: NiveauScolaire::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NiveauScolaire $niveauScolaire = null;

    #[ORM\OneToOne(mappedBy: 'utilisateur', cascade: ['persist', 'remove'])]
    private ?UserLog $userLog = null;

    /**
     * @var Collection<int, Agent>
     */
    #[ORM\ManyToMany(targetEntity: Agent::class, mappedBy: 'utilisateurs')]
    private Collection $agents;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'user')]
    private Collection $conversations;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->conversations = new ArrayCollection();
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

    public function getNiveauScolaire(): ?NiveauScolaire
    {
        return $this->niveauScolaire;
    }

    public function setNiveauScolaire(?NiveauScolaire $niveauScolaire): static
    {
        $this->niveauScolaire = $niveauScolaire;

        return $this;
    }

    public function getUserLog(): ?UserLog
    {
        return $this->userLog;
    }

    public function setUserLog(?UserLog $userLog): static
    {
        // unset the owning side of the relation if necessary
        if ($userLog === null && $this->userLog !== null) {
            $this->userLog->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($userLog !== null && $userLog->getUtilisateur() !== $this) {
            $userLog->setUtilisateur($this);
        }

        $this->userLog = $userLog;

        return $this;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): static
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->addUtilisateur($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): static
    {
        if ($this->agents->removeElement($agent)) {
            $agent->removeUtilisateur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

        return $this;
    }
}
