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

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 9)]
    private ?string $phone = null;

    #[ORM\Column(length: 250)]
    private ?string $photo = null;

    /**
     * @var Collection<int, ActivityMonitor>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitor::class, mappedBy: 'monitor', orphanRemoval: true)]
    private Collection $ActivitiesOfMonitor;

    public function __construct()
    {
        $this->ActivitiesOfMonitor = new ArrayCollection();
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
     * @return Collection<int, ActivityMonitor>
     */
    public function getActivitiesOfMonitor(): Collection
    {
        return $this->ActivitiesOfMonitor;
    }

    public function addActivitiesOfMonitor(ActivityMonitor $activitiesOfMonitor): static
    {
        if (!$this->ActivitiesOfMonitor->contains($activitiesOfMonitor)) {
            $this->ActivitiesOfMonitor->add($activitiesOfMonitor);
            $activitiesOfMonitor->setMonitor($this);
        }

        return $this;
    }

    public function removeActivitiesOfMonitor(ActivityMonitor $activitiesOfMonitor): static
    {
        if ($this->ActivitiesOfMonitor->removeElement($activitiesOfMonitor)) {
            // set the owning side to null (unless already changed)
            if ($activitiesOfMonitor->getMonitor() === $this) {
                $activitiesOfMonitor->setMonitor(null);
            }
        }

        return $this;
    }
}
