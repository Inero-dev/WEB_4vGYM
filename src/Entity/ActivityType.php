<?php

namespace App\Entity;

use App\Repository\ActivityTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityTypeRepository::class)]
class ActivityType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $NumerOfMonitors = null;

    #[ORM\ManyToOne(inversedBy: 'idType')]
    private ?Activity $activities = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNumerOfMonitors(): ?int
    {
        return $this->NumerOfMonitors;
    }

    public function setNumerOfMonitors(int $NumerOfMonitors): static
    {
        $this->NumerOfMonitors = $NumerOfMonitors;

        return $this;
    }

    public function getActivities(): ?Activity
    {
        return $this->activities;
    }

    public function setActivities(?Activity $activities): static
    {
        $this->activities = $activities;

        return $this;
    }
}
