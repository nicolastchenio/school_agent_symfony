<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(length: 50)]
    private ?string $yes = null;

    #[ORM\ManyToOne(inversedBy: 'agent')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'agent')]
    private ?Agent $agent = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'conversation')]
    private Collection $conversation;

    public function __construct()
    {
        $this->conversation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getYes(): ?string
    {
        return $this->yes;
    }

    public function setYes(string $yes): static
    {
        $this->yes = $yes;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getConversation(): Collection
    {
        return $this->conversation;
    }

    public function addConversation(Message $conversation): static
    {
        if (!$this->conversation->contains($conversation)) {
            $this->conversation->add($conversation);
            $conversation->setConversation($this);
        }

        return $this;
    }

    public function removeConversation(Message $conversation): static
    {
        if ($this->conversation->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getConversation() === $this) {
                $conversation->setConversation(null);
            }
        }

        return $this;
    }
}
