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

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActivityType $type = null;

    /**
     * @var Collection<int, ActivityMonitor>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitor::class, mappedBy: 'Activity', orphanRemoval: true)]
    private Collection $monitorsOfActivity;

    public function __construct()
    {
        $this->monitorsOfActivity = new ArrayCollection();
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

    public function getType(): ?ActivityType
    {
        return $this->type;
    }

    public function setType(?ActivityType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitor>
     */
    public function getMonitorsOfActivity(): Collection
    {
        return $this->monitorsOfActivity;
    }

    public function addMonitorsOfActivity(ActivityMonitor $monitorsOfActivity): static
    {
        if (!$this->monitorsOfActivity->contains($monitorsOfActivity)) {
            $this->monitorsOfActivity->add($monitorsOfActivity);
            $monitorsOfActivity->setActivity($this);
        }

        return $this;
    }

    public function removeMonitorsOfActivity(ActivityMonitor $monitorsOfActivity): static
    {
        if ($this->monitorsOfActivity->removeElement($monitorsOfActivity)) {
            // set the owning side to null (unless already changed)
            if ($monitorsOfActivity->getActivity() === $this) {
                $monitorsOfActivity->setActivity(null);
            }
        }

        return $this;
    }
}
