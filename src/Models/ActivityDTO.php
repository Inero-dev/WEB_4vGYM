<?php

namespace App\Models;
use DateTime; 

class ActivityDTO //TODO MIRAR SI ES NECESARIO VALIDAR (MonitorDTO ARRAY) Y MIRAR SI SE NECESITA ADDMONITOR
{
    private int $id;
    private string $name;
    private ActivityTypeDTO $activityType;
    private array $monitors; //MonitorDTO
    private DateTime $start_date;
    private DateTime $end_date;

    public function __construct(int $id, string $name, ActivityTypeDTO $activityType, array $monitors, DateTime $start_date, DateTime $end_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->activityType = $activityType;
        $this->monitors = $monitors;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getActivityType(): ActivityTypeDTO
    {
        return $this->activityType;
    }

    public function getMonitors(): array
    {
        return $this->monitors;
    }

    public function getStartDate(): DateTime
    {
        return $this->start_date;
    }

    public function getEndDate(): DateTime
    {
        return $this->end_date;
    }
}
