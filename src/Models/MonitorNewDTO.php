<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

class MonitorNewDTO
{
    public function __construct(
        #[Assert\NotBlank(message:"El nombre es obligatorio")]
        public string $name,
        #[Assert\NotBlank(message:"El email es obligatorio")]
        public string $email,
        #[Assert\NotBlank(message:"El número es obligatorio")]
        public string $phone,
        #[Assert\NotBlank(message:"La foto es obligatorio")]
        public string $photo
        ){}
        
       
}
