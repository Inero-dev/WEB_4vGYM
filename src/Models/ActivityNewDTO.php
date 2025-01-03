<?php

namespace App\Models;

use DateTime; 
use App\Models\ActivityTypeDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ActivityNewDTO
{


    public function __construct(
        #[Assert\NotBlank(message: "El tipo de actividad es obligatorio")]
        private int $idType,
        #[Assert\NotBlank(message: "Los monitores son obligatorios")]
        private array $monitors, //MonitorDTO
        #[Assert\NotBlank(message: "La fecha de inicio es obligatoria")]
        private DateTime $start_date,
        #[Assert\NotBlank(message: "La fecha de fin es obligatoria")]
        private DateTime $end_date,
    ) {}
    public function getIdType(): int
    {
        return $this->idType;
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
