<?php

namespace App\Entity;

use App\Repository\ActivityMonitorsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityMonitorsRepository::class)]
class ActivityMonitors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $Activity = null;

    #[ORM\ManyToOne(inversedBy: 'Monitors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monitor $Monitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->Activity;
    }

    public function setActivity(?Activity $Activity): static
    {
        $this->Activity = $Activity;

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->Monitor;
    }

    public function setMonitor(?Monitor $Monitor): static
    {
        $this->Monitor = $Monitor;

        return $this;
    }
}
