<?php

namespace App\Entity;

use App\Repository\ActivityMonitorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityMonitorRepository::class)]
class ActivityMonitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'monitorsOfActivity')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $Activity = null;

    #[ORM\ManyToOne(inversedBy: 'ActivitiesOfMonitor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monitor $monitor = null;

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
        return $this->monitor;
    }

    public function setMonitor(?Monitor $monitor): static
    {
        $this->monitor = $monitor;

        return $this;
    }
}
