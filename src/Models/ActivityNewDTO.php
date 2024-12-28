<?php

namespace App\Models;

use DateTime; 
use App\Models\ActivityTypeDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ActivityNewDTO
{
    public function __construct(
        public int $id,
        private ActivityTypeDTO $activityType,
        private array $monitors, //MonitorDTO
        private DateTime $start_date,
        private DateTime $end_date,
    ) {}
    // Getter para 'activityType'
    public function getActivityType(): ActivityTypeDTO
    {
        return $this->activityType;
    }

    // Getter para 'monitors'
    public function getMonitors(): array
    {
        return $this->monitors;
    }

    // Getter para 'start_date'
    public function getStartDate(): DateTime
    {
        return $this->start_date;
    }

    // Getter para 'end_date'
    public function getEndDate(): DateTime
    {
        return $this->end_date;
    }

}
