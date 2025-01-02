<?php

namespace App\Entity;

use App\Repository\MonitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonitorRepository::class)]
class Monitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 9)]
    private ?string $phone = null;

    #[ORM\Column(length: 250)]
    private ?string $photo = null;

    /**
     * @var Collection<int, ActivityMonitors>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitors::class, mappedBy: 'Monitor', orphanRemoval: true)]
    private Collection $Monitors;

    public function __construct()
    {
        $this->Monitors = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitors>
     */
    public function getMonitors(): Collection
    {
        return $this->Monitors;
    }

    public function addMonitor(ActivityMonitors $monitor): static
    {
        if (!$this->Monitors->contains($monitor)) {
            $this->Monitors->add($monitor);
            $monitor->setMonitor($this);
        }

        return $this;
    }

    public function removeMonitor(ActivityMonitors $monitor): static
    {
        if ($this->Monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getMonitor() === $this) {
                $monitor->setMonitor(null);
            }
        }

        return $this;
    }

}
