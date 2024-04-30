<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EncounterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncounterRepository::class)]
#[ApiResource]
class Encounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'encounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\Column(length: 255)]
    private ?string $opponent = null;

    #[ORM\Column]
    private ?bool $isAtHome = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $happensAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isVictory = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getOpponent(): ?string
    {
        return $this->opponent;
    }

    public function setOpponent(string $opponent): static
    {
        $this->opponent = $opponent;

        return $this;
    }

    public function isAtHome(): ?bool
    {
        return $this->isAtHome;
    }

    public function setAtHome(bool $isAtHome): static
    {
        $this->isAtHome = $isAtHome;

        return $this;
    }

    public function getHappensAt(): ?\DateTimeImmutable
    {
        return $this->happensAt;
    }

    public function setHappensAt(\DateTimeImmutable $happensAt): static
    {
        $this->happensAt = $happensAt;

        return $this;
    }

    public function isVictory(): ?bool
    {
        return $this->isVictory;
    }

    public function setVictory(?bool $isVictory): static
    {
        $this->isVictory = $isVictory;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
