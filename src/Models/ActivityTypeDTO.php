<?php

namespace App\Models;

class ActividadDTO //TODO GENERAR SETERS SI ES NECESARIO
{
    private int $id;
    private string $name;
    private int $number_monitors;

    public function __construct(int $id, string $name, int $number_monitors)
    {
        $this->id = $id;
        $this->name = $name;
        $this->number_monitors = $number_monitors;
    }   

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumberMonitors(): int
    {
        return $this->number_monitors;
    }

    

    
}
