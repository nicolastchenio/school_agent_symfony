<?php

namespace App\Entity;

use App\Repository\UserLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLogRepository::class)]
class UserLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $derniereConnection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDerniereConnection(): ?string
    {
        return $this->derniereConnection;
    }

    public function setDerniereConnection(string $derniereConnection): static
    {
        $this->derniereConnection = $derniereConnection;

        return $this;
    }
}
