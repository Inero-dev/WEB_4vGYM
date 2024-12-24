<?php

namespace App\Models;

class ActividadDTO
{
    private int $id;
    private string $name;
    private string $email;
    private string $phone;
    private string $photo;

    public function __construct(int $id, string $name, string $email, string $phone, string $photo)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->photo = $photo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;    
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }
}
