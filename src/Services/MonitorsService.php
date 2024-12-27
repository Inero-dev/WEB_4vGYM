<?php

namespace App\Services;

use App\Models\MonitorDTO;
use App\Entity\Monitor;
use App\Models\MonitorNewDTO;
use Doctrine\ORM\EntityManagerInterface;

class MonitorsService
{

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getMonitors(): array
    {
        //return $this->entityManager->getRepository(ActivityType::class)->findAll(); //busca de la bbdd todos los de clase Tipo

        $monitors = $this->entityManager->getRepository(Monitor::class)->findAll();

        $monitorsDTO = [];
        foreach ($monitors as $monitor) {
            log($monitor->getId());
            $monitorsDTO[] = new MonitorDTO($monitor->getId(), $monitor->getName(), $monitor->getEmail(), $monitor->getPhone(), $monitor->getPhoto()); //los convierte en DTO modelos
        }

        return $monitorsDTO;
    }

    public function addMonitor(MonitorNewDTO $monitor): MonitorDTO
    {
        // Creamos la entidad restaurante
        $newMonitorEntity = new Monitor();
        $newMonitorEntity->setName($monitor->name);
        $newMonitorEntity->setEmail($monitor->email);
        $newMonitorEntity->setPhone($monitor->phone);
        $newMonitorEntity->setPhoto($monitor->photo);

        // Le dices a Doctrine que quieres persistit el objeto,, todavia no hace nada
        $this->entityManager->persist($newMonitorEntity);
        // Aqui es donde confirmas, asi tienes el concepto de transaccion!!!!
        $this->entityManager->flush();
        // Fijate que se ha cambiado la entidad con el ID nuevo
        return new MonitorDTO($newMonitorEntity->getId(), $newMonitorEntity->getName(), $newMonitorEntity->getEmail(), $newMonitorEntity->getPhone(), $newMonitorEntity->getPhoto());
    }

    public function updateMonitor(MonitorNewDTO $monitorNew, int $id): void
    {
        $oldMonitorEntity = $this->entityManager->getRepository(Monitor::class)->findOneBy(['id' => $id]);

        // Verificar si la entidad existe
        if (!$oldMonitorEntity) {
            throw new \Exception("Monitor con ID $id no encontrado");
        }

        // Actualizar las propiedades de la entidad con los datos del DTO
        $oldMonitorEntity->setName($monitorNew->name);
        $oldMonitorEntity->setEmail($monitorNew->email);
        $oldMonitorEntity->setPhone($monitorNew->phone);
        $oldMonitorEntity->setPhoto($monitorNew->photo);

        // Persistir los cambios en la base de datos
        $this->entityManager->persist($oldMonitorEntity);
        $this->entityManager->flush();
    }

    public function deleteMonitor(int $id): void
    {
        $monitorEntity = $this->entityManager->getRepository(Monitor::class)->findOneBy(['id' => $id]);
        if (!$monitorEntity) {
            throw new \Exception("Monitor con ID $id no encontrado");
        }
        $this->entityManager->remove($monitorEntity);
        $this->entityManager->flush();
    }
}
