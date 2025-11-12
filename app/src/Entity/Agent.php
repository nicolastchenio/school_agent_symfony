<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $promptSystem = null;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'agent')]
    private Collection $agent;

    public function __construct()
    {
        $this->agent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPromptSystem(): ?string
    {
        return $this->promptSystem;
    }

    public function setPromptSystem(string $promptSystem): static
    {
        $this->promptSystem = $promptSystem;

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
            $agent->setAgent($this);
        }

        return $this;
    }

    public function removeAgent(Conversation $agent): static
    {
        if ($this->agent->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getAgent() === $this) {
                $agent->setAgent(null);
            }
        }

        return $this;
    }
}
