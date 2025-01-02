<?php

namespace App\Services;

use App\Models\ActivityTypeDTO;
use App\Entity\ActivityType;
use Doctrine\ORM\EntityManagerInterface;

class ActivityTypesService
{
    
    public function __construct(private EntityManagerInterface $entityManager){}

    public function getTypes(): array
    {
        //return $this->entityManager->getRepository(ActivityType::class)->findAll(); //busca de la bbdd todos los de clase Tipo
        
        $types = $this->entityManager->getRepository(ActivityType::class)->findAll();
        
        $typesDTO = [];
        foreach ($types as $type) {
            log($type->getId());
            $typesDTO[] = new ActivityTypeDTO($type->getId(), $type->getName(), $type->getNumberOfMonitors()); //los convierte en DTO modelos
        }

        return $typesDTO;
        
    }
    
}
