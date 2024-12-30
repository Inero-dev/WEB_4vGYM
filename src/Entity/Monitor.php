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
     * @var Collection<int, ActivityMonitor>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitor::class, mappedBy: 'idMonitor', orphanRemoval: true)]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
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
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(ActivityMonitor $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setIdMonitor($this);
        }

        return $this;
    }

    public function removeActivity(ActivityMonitor $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getIdMonitor() === $this) {
                $activity->setIdMonitor(null);
            }
        }

        return $this;
    }

}
