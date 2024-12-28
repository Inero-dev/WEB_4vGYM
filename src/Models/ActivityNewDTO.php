<?php

namespace App\Model;

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


}
