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

    public function getListActivities(?string $date = null): array //TODO falta el filtrado
    {

        if ($date) {
            // Convertimos la fecha del formato 'dd-MM-yyyy' al formato DateTime
            $dateObj = \DateTime::createFromFormat('d-m-Y', $date);
            if (!$dateObj) {
                throw new \InvalidArgumentException("Formato de fecha no válido. Debe ser dd-MM-yyyy.");
            }

            $activities = $this->entityManager->getRepository(Activity::class)->findBy(['startDate' => $dateObj]);
        } else {

            $activities = $this->entityManager->getRepository(Activity::class)->findAll();
        }

        $activities = $this->entityManager->getRepository(Activity::class)->findAll();

        $listActivitiesDTO = [];

        foreach ($activities as $activity) {
            log($activity->getId());

            $type = $this->entityManager->getRepository(ActivityType::class)->findOneBy(['id' => $activity->getActivityTypeId()]);
            $typeDTO = new ActivityTypeDTO($type->getId(), $type->getName(), $type->getNumberMonitors());


            $activityMonitors = $this->entityManager->getRepository(ActivityMonitors::class)->findBy(['idActivity' => $activity->getId()]);
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
    
     public function addActivity(ActivityNewDTO $activityNewDTO): ActivityDTO
    {
        //VALIDACIONES
        //validar que la hora de inicio sea una de las 3s: 09:00, 13:30, 17:30
        $validStartTimes = ['09:00', '13:30', '17:30'];
        $startTime = $activityNewDTO->getStartDate()->format('H:i');

        if (!in_array($startTime, $validStartTimes)) {
            throw new \Exception('La actividad debe comenzar a las 09:00, 13:30 o 17:30');
        }

        //validar que la duración de la actividad sea de 90 minutos
        $duration = $activityNewDTO->getEndDate()->getTimestamp() - $activityNewDTO->getStartDate()->getTimestamp();
        if ($duration !== 90 * 60) {
            throw new \Exception('La actividad debe tener una duración de 90 minutos');
        }

        // Validación de monitores (comprobamos el número de monitores por tipo de actividad)
        $activityTypeId = $activityNewDTO->getIdType();
        $activityTypeEntity = $this->entityManager->getRepository(ActivityType::class)->find($activityTypeId);
        $requiredMonitors = $activityTypeEntity->getNumberMonitors();

        if (count($activityNewDTO->getMonitors()) < $requiredMonitors) {
            throw new \Exception("La actividad requiere al menos {$requiredMonitors} monitores.");
        }

        //Craemos la entidad ACTIVITY
        $newActivityEntity = new Activity();
        $newActivityEntity->setActivtyType(new ActivityType($activityNewDTO->getIdType()));
        $newActivityEntity->setStartDate($activityNewDTO->getStartDate()); 
        $newActivityEntity->setEndDate($activityNewDTO->getEndDate()); 

        // Persistir la actividad
        $this->entityManager->persist($newActivityEntity);

        // Confirmar la transacción
        $this->entityManager->flush();

        // Crear y persistir los monitores relacionados
        foreach ($activityNewDTO->getMonitors() as  $monitorDTO) {
            $activityMonitor = new ActivityMonitors();
            $activityMonitor->setActivity($newActivityEntity);
            $activityMonitor->setMonitor(new Monitor($monitorDTO->getId())); // ID del monitor
            $this->entityManager->persist($activityMonitor);
            
        }



        // Construir un DTO del tipo de actividad
        $activityTypeEntity = $this->entityManager->getRepository(ActivityType::class)->find($activityNewDTO->getIdType());
        $activityTypeDTO = new ActivityTypeDTO(
            $activityTypeEntity->getId(),
            $activityTypeEntity->getName(),
            $activityTypeEntity->getNumberMonitors()
        );

        // Obtener los monitores relacionados y construir MonitorDTOs
        $monitors = [];
        foreach ($activityNewDTO->getMonitors() as $monitorDTO) {
            $monitorEntity = $this->entityManager->getRepository(Monitor::class)->find($monitorDTO);

            $monitors[] = new MonitorDTO($monitorEntity->getId(), $monitorEntity->getName(), $monitorEntity->getEmail(), $monitorEntity->getPhone(), $monitorEntity->getPhoto());
        }

        //pasar la entidad a un DTO

        $activityDTO = new ActivityDTO($newActivityEntity->getId(),$activityTypeDTO, $monitors,$newActivityEntity->getStartDate(),$newActivityEntity->getEndDate());
        return $activityDTO;

    }

    public function updateActivity(ActivityNewDTO $activityNew, int $id): void
    {
        // buscar la actividad existente por id
        $oldActivityEntity = $this->entityManager->getRepository(Activity::class)->findOneBy(['id' => $id]);

        // Verificar si existe
        if (!$oldActivityEntity) {
            throw new \Exception("Actividad con ID $id no encontrada");
        }

        // Validar la hora de inicio permitida
        $validStartTimes = ['09:00', '13:30', '17:30'];
        $startTime = $activityNew->getStartDate()->format('H:i');
        if (!in_array($startTime, $validStartTimes)) {
            throw new \Exception("La actividad debe comenzar a las 09:00, 13:30, o 17:30");
        }

        // Validar la duración (90 minutos)
        $duration = $activityNew->getStartDate()->diff($activityNew->getEndDate());
        if ($duration->h !== 1 || $duration->i !== 30) {
            throw new \Exception("La duración de la actividad debe ser de exactamente 90 minutos");
        }

        // Validar que el número de monitores es igual al requerido por el tipo de actividad
        $activityType = $this->entityManager->getRepository(ActivityType::class)->find($activityNew->getIdType());
        if (!$activityType) {
            throw new \Exception("Tipo de actividad no encontrado");
        }

        if (count($activityNew->getMonitors()) !== $activityType->getNumberMonitors()) {
            throw new \Exception("El número de monitores no coincide con el requerido por el tipo de actividad");
        }

        // Actualizar las propiedades de la actividad
        $oldActivityEntity->setActivityTypeId($activityNew->getIdType());
        $oldActivityEntity->setStartDate($activityNew->getStartDate());
        $oldActivityEntity->setEndDate($activityNew->getEndDate());

        // Eliminar monitores anteriores relacionados con esta actividad
        $oldMonitors = $this->entityManager->getRepository(ActivityMonitors::class)->findBy(['idActivity' => $id]);
        foreach ($oldMonitors as $oldMonitor) {
            $this->entityManager->remove($oldMonitor);
        }

        // Agregar nuevos monitores
        foreach ($activityNew->getMonitors() as $monitorDTO) {
            $activityMonitor = new ActivityMonitors();
            $activityMonitor->setActivity(new Activity($id));
            $activityMonitor->setMonitor(new Monitor($monitorDTO->getId()));
            $this->entityManager->persist($activityMonitor);
        }

        // Persistir los cambios en la base de datos
        $this->entityManager->persist($oldActivityEntity);
        $this->entityManager->flush();
    }

    public function deleteActivity(int $id): void
    {
        $activityEntity = $this->entityManager->getRepository(Activity::class)->findOneBy(['id' => $id]);

        if (!$activityEntity) {
            throw new \Exception("Actividad con ID $id no encontrada");
        }

        // Eliminar monitores relacionados con la actividad
        $activityMonitors = $this->entityManager->getRepository(ActivityMonitors::class)->findBy(['idActivity' => $id]);
        foreach ($activityMonitors as $activityMonitor) {
            $this->entityManager->remove($activityMonitor);
        }

        $this->entityManager->remove($activityEntity);
        $this->entityManager->flush();
    }

}
