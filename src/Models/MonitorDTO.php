<?php

namespace App\Models;


class MonitorDTO
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

    //genera los getters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function json(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => $this->photo,
        ];
    }
}
