<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\ManyToOne(inversedBy: 'Activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActivityType $ActivtyType = null;

    /**
     * @var Collection<int, ActivityMonitors>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitors::class, mappedBy: 'Activity', orphanRemoval: true)]
    private Collection $Activities;

    public function __construct()
    {
        $this->Activities = new ArrayCollection();
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

    public function getActivtyType(): ?ActivityType
    {
        return $this->ActivtyType;
    }

    public function setActivtyType(?ActivityType $ActivtyType): static
    {
        $this->ActivtyType = $ActivtyType;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitors>
     */
    public function getActivities(): Collection
    {
        return $this->Activities;
    }

    public function addActivity(ActivityMonitors $activity): static
    {
        if (!$this->Activities->contains($activity)) {
            $this->Activities->add($activity);
            $activity->setActivity($this);
        }

        return $this;
    }

    public function removeActivity(ActivityMonitors $activity): static
    {
        if ($this->Activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getActivity() === $this) {
                $activity->setActivity(null);
            }
        }

        return $this;
    }

}
