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

    #[ORM\Column]
    private ?int $idActivity = null;

    #[ORM\Column]
    private ?int $idMonitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getidActivity(): ?int
    {
        return $this->idActivity;
    }

    public function setidActivity(int $idActivity): static
    {
        $this->idActivity = $idActivity;

        return $this;
    }

    public function getIdMonitor(): ?int
    {
        return $this->idMonitor;
    }

    public function setIdMonitor(int $idMonitor): static
    {
        $this->idMonitor = $idMonitor;

        return $this;
    }
}
