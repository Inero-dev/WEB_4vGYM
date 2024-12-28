<?php

namespace App\Services;

use App\Models\ActivityDTO;
use App\Models\ActivityTypeDTO;
use App\Models\MonitorDTO;
use App\Models\ActivityNewDTO;

use App\Entity\Activity;
use App\Entity\ActivityMonitors;
use App\Entity\Monitor;
use App\Entity\ActivityType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ActivityService
{

    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {}

    public function getListActivities(): array
    {

        $activities = $this->entityManager->getRepository(Activity::class)->findAll();

        $listActivitiesDTO = [];

        foreach ($activities as $activity) {
            log($activity->getId());

            $type = $this->entityManager->getRepository(ActivityType::class)->findOneBy(['id' => $activity->getActivityTypeId()]);
            $typeDTO = new ActivityTypeDTO($type->getId(), $type->getName(), $type->getNumberMonitors());


            $activityMonitors = $this->entityManager->getRepository(ActivityMonitors::class)->findBy(['id_activity' => $activity->getId()]);
            $monitorDTOs = [];

            foreach ($activityMonitors as $activityMonitor) {
                // Obtener cada monitor por su ID y convertirlo a DTO
                $monitor = $this->entityManager->getRepository(Monitor::class)->findOneBy(['id' => $activityMonitor->getIdMonitor()]);
                if ($monitor) {
                    $monitorDTOs[] = new MonitorDTO(
                        $monitor->getId(),
                        $monitor->getName(),
                        $monitor->getEmail(),
                        $monitor->getPhone(),
                        $monitor->getPhoto()
                    );
                }
            }

            // Crear el DTO de la actividad con todos los datos
            $listActivitiesDTO[] = new ActivityDTO(
                $activity->getId(),
                $typeDTO,
                $monitorDTOs,
                $activity->getStartDate(),
                $activity->getEndDate()
            );
        }

        return $listActivitiesDTO;
    }
     public function addActivity(array $activityData): ActivityDTO
    {
        //Craemos la entidad ACTIVITY
        $newActivityEntity = new Activity();
        $newActivityEntity->setName(''); //Este no está en wl Swagger. Pero es necesario? Dejo del nombre vacío porque no viene en el JSON
        $newActivityEntity->setActivityTypeId($activityData['activity_type_id']);
        $newActivityEntity->setStartDate(new \DateTime($activityData['date_start']));
        $newActivityEntity->setEndDate(new \DateTime($activityData['date_end']));

        // Persistir la actividad
        $this->entityManager->persist($newActivityEntity);

        // Crear y persistir los monitores relacionados
        foreach ($activityData['monitors_id'] as $monitorId) {
            $activityMonitor = new ActivityMonitors();
            $activityMonitor->setIdActivity($newActivityEntity->getId());
            $activityMonitor->setIdMonitor($monitorId);
            $this->entityManager->persist($activityMonitor);
        }

        // Confirmar la transacción
        $this->entityManager->flush();

        // Construir un DTO del tipo de actividad
        $activityTypeEntity = $this->entityManager->getRepository(ActivityType::class)->find($activityData['activity_type_id']);
        $activityTypeDTO = new ActivityTypeDTO(
            $activityTypeEntity->getId(),
            $activityTypeEntity->getName(),
            $activityTypeEntity->getNumberMonitors()
        );

        // Obtener los monitores relacionados y construir MonitorDTOs
        $monitors = [];
        foreach ($activityData['monitors_id'] as $monitorId) {
            $monitorEntity = $this->entityManager->getRepository(Monitor::class)->find($monitorId);
            $monitors[] = new MonitorDTO($monitorEntity->getId(), $monitorEntity->getName(), $monitorEntity->getEmail(), $monitorEntity->getPhone(), $monitorEntity->getPhoto());
        }

        // Mapear la entidad a un DTO

        $activityDTO = new ActivityDTO(
            $newActivityEntity->getId(),
            $activityTypeDTO,
            $monitors,
            $newActivityEntity->getStartDate(),
            $newActivityEntity->getEndDate()
        );
        return $activityDTO;

    }

}
