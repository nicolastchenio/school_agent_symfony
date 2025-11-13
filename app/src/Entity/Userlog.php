<?php

namespace App\Entity;

use App\Repository\UserlogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserlogRepository::class)]
class Userlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dernierConnection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDernierConnection(): ?\DateTime
    {
        return $this->dernierConnection;
    }

    public function setDernierConnection(\DateTime $dernierConnection): static
    {
        $this->dernierConnection = $dernierConnection;

        return $this;
    }
}
