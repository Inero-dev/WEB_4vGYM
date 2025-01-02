<?php

namespace App\Services;

use App\Models\ActivityDTO;
use App\Models\ActivityTypeDTO;
use App\Models\MonitorDTO;
use App\Models\ActivityNewDTO;

use App\Entity\Activity;
use App\Entity\ActivityMonitor;
use App\Entity\Monitor;
use App\Entity\ActivityType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ActivityService
{
    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer) {}

    public function getListActivities(?string $date = null): array
    {
        if ($date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $date);
            if (!$dateObj) {
                throw new \InvalidArgumentException("Formato de fecha no válido. Debe ser dd-MM-yyyy.");
            }
            $activities = $this->entityManager->getRepository(Activity::class)->findBy(['startDate' => $dateObj]);
        } else {
            $activities = $this->entityManager->getRepository(Activity::class)->findAll();
        }

        $listActivitiesDTO = [];

        foreach ($activities as $activity) {
            $type = $activity->getType();
            $typeDTO = new ActivityTypeDTO($type->getId(), $type->getName(), $type->getNumberOfMonitors());

            $activityMonitors = $this->entityManager->getRepository(ActivityMonitor::class)->findBy(['Activity' => $activity]);
            $monitorDTOs = [];

            foreach ($activityMonitors as $activityMonitor) {
                $monitor = $activityMonitor->getMonitor();
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
        $validStartTimes = ['09:00', '13:30', '17:30'];
        $startTime = $activityNewDTO->getStartDate()->format('H:i');

        if (!in_array($startTime, $validStartTimes)) {
            throw new \Exception('La actividad debe comenzar a las 09:00, 13:30 o 17:30');
        }

        $duration = $activityNewDTO->getEndDate()->getTimestamp() - $activityNewDTO->getStartDate()->getTimestamp();
        if ($duration !== 90 * 60) {
            throw new \Exception('La actividad debe tener una duración de 90 minutos');
        }

        $activityType = $this->entityManager->getRepository(ActivityType::class)->find($activityNewDTO->getIdType());
        if (!$activityType) {
            throw new \Exception("Tipo de actividad no encontrado");
        }

        $requiredMonitors = $activityType->getNumberOfMonitors();
        if (count($activityNewDTO->getMonitors()) < $requiredMonitors) {
            throw new \Exception("La actividad requiere al menos {$requiredMonitors} monitores.");
        }

        $newActivityEntity = new Activity();
        $newActivityEntity->setType($activityType);
        $newActivityEntity->setStartDate($activityNewDTO->getStartDate());
        $newActivityEntity->setEndDate($activityNewDTO->getEndDate());

        $this->entityManager->persist($newActivityEntity);

        foreach ($activityNewDTO->getMonitors() as $monitorId) {
            // Aquí esperamos que $monitorId sea un entero, no un objeto
            $monitor = $this->entityManager->getRepository(Monitor::class)->find($monitorId);
            if (!$monitor) {
                throw new \Exception("Monitor con ID {$monitorId} no encontrado");
            }

            $activityMonitor = new ActivityMonitor();
            $activityMonitor->setActivity($newActivityEntity);
            $activityMonitor->setMonitor($monitor);

            $this->entityManager->persist($activityMonitor);
        }

        $this->entityManager->flush();

        $activityTypeDTO = new ActivityTypeDTO(
            $activityType->getId(),
            $activityType->getName(),
            $activityType->getNumberOfMonitors()
        );

        $monitorDTOs = [];
        foreach ($activityNewDTO->getMonitors() as $monitorId) {
            $monitorEntity = $this->entityManager->getRepository(Monitor::class)->find($monitorId);
            $monitorDTOs[] = new MonitorDTO(
                $monitorEntity->getId(),
                $monitorEntity->getName(),
                $monitorEntity->getEmail(),
                $monitorEntity->getPhone(),
                $monitorEntity->getPhoto()
            );
        }

        return new ActivityDTO(
            $newActivityEntity->getId(),
            $activityTypeDTO,
            $monitorDTOs,
            $newActivityEntity->getStartDate(),
            $newActivityEntity->getEndDate()
        );
    }

    public function updateActivity(ActivityNewDTO $activityNew, int $id): void
    {
        $oldActivityEntity = $this->entityManager->getRepository(Activity::class)->find($id);
        if (!$oldActivityEntity) {
            throw new \Exception("Actividad con ID $id no encontrada");
        }

        $validStartTimes = ['09:00', '13:30', '17:30'];
        $startTime = $activityNew->getStartDate()->format('H:i');
        if (!in_array($startTime, $validStartTimes)) {
            throw new \Exception("La actividad debe comenzar a las 09:00, 13:30, o 17:30");
        }

        $duration = $activityNew->getStartDate()->diff($activityNew->getEndDate());
        if ($duration->h !== 1 || $duration->i !== 30) {
            throw new \Exception("La duración de la actividad debe ser de exactamente 90 minutos");
        }

        $activityType = $this->entityManager->getRepository(ActivityType::class)->find($activityNew->getIdType());
        if (!$activityType) {
            throw new \Exception("Tipo de actividad no encontrado");
        }

        $oldActivityEntity->setType($activityType);
        $oldActivityEntity->setStartDate($activityNew->getStartDate());
        $oldActivityEntity->setEndDate($activityNew->getEndDate());

        $oldMonitors = $this->entityManager->getRepository(ActivityMonitor::class)->findBy(['Activity' => $oldActivityEntity]);
        foreach ($oldMonitors as $oldMonitor) {
            $this->entityManager->remove($oldMonitor);
        }

        foreach ($activityNew->getMonitors() as $monitorId) {
            $monitor = $this->entityManager->getRepository(Monitor::class)->find($monitorId);
            if (!$monitor) {
                throw new \Exception("Monitor con ID {$monitorId} no encontrado");
            }

            $activityMonitor = new ActivityMonitor();
            $activityMonitor->setActivity($oldActivityEntity);
            $activityMonitor->setMonitor($monitor);

            $this->entityManager->persist($activityMonitor);
        }

        $this->entityManager->flush();
    }

    public function deleteActivity(int $id): void
    {
        $activityEntity = $this->entityManager->getRepository(Activity::class)->find($id);

        if (!$activityEntity) {
            throw new \Exception("Actividad con ID $id no encontrada");
        }

        $activityMonitors = $this->entityManager->getRepository(ActivityMonitor::class)->findBy(['Activity' => $activityEntity]);
        foreach ($activityMonitors as $activityMonitor) {
            $this->entityManager->remove($activityMonitor);
        }

        $this->entityManager->remove($activityEntity);
        $this->entityManager->flush();
    }
}
