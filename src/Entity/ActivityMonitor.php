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

    #[ORM\ManyToOne(inversedBy: 'monitors')]
    private ?Activity $idActivity = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monitor $idMonitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdActivity(): ?Activity
    {
        return $this->idActivity;
    }

    public function setIdActivity(?Activity $idActivity): static
    {
        $this->idActivity = $idActivity;

        return $this;
    }

    public function getIdMonitor(): ?Monitor
    {
        return $this->idMonitor;
    }

    public function setIdMonitor(?Monitor $idMonitor): static
    {
        $this->idMonitor = $idMonitor;

        return $this;
    }
}
