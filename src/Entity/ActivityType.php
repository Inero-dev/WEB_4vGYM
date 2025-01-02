<?php

namespace App\Entity;

use App\Repository\ActivityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityTypeRepository::class)]
class ActivityType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $NumerOfMonitors = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'ActivtyType', orphanRemoval: true)]
    private Collection $Activities;

    public function __construct()
    {
        $this->Activities = new ArrayCollection();
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

    public function getNumerOfMonitors(): ?int
    {
        return $this->NumerOfMonitors;
    }

    public function setNumerOfMonitors(int $NumerOfMonitors): static
    {
        $this->NumerOfMonitors = $NumerOfMonitors;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->Activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->Activities->contains($activity)) {
            $this->Activities->add($activity);
            $activity->setActivtyType($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->Activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getActivtyType() === $this) {
                $activity->setActivtyType(null);
            }
        }

        return $this;
    }
}
