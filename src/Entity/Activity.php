<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    /**
     * @var Collection<int, ActivityMonitor>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitor::class, mappedBy: 'idActivity')]
    private Collection $monitors;

    /**
     * @var Collection<int, ActivityType>
     */
    #[ORM\OneToMany(targetEntity: ActivityType::class, mappedBy: 'activities')]
    private Collection $idType;

    public function __construct()
    {
        $this->monitors = new ArrayCollection();
        $this->idType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitor>
     */
    public function getMonitors(): Collection
    {
        return $this->monitors;
    }

    public function addMonitor(ActivityMonitor $monitor): static
    {
        if (!$this->monitors->contains($monitor)) {
            $this->monitors->add($monitor);
            $monitor->setIdActivity($this);
        }

        return $this;
    }

    public function removeMonitor(ActivityMonitor $monitor): static
    {
        if ($this->monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getIdActivity() === $this) {
                $monitor->setIdActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityType>
     */
    public function getidType(): Collection
    {
        return $this->idType;
    }

    public function addidType(ActivityType $idType): static
    {
        if (!$this->idType->contains($idType)) {
            $this->idType->add($idType);
            $idType->setActivities($this);
        }

        return $this;
    }

    public function removeidType(ActivityType $idType): static
    {
        if ($this->idType->removeElement($idType)) {
            // set the owning side to null (unless already changed)
            if ($idType->getActivities() === $this) {
                $idType->setActivities(null);
            }
        }

        return $this;
    }
}
