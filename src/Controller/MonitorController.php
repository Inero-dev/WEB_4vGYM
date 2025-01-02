<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\MonitorsService;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MonitorNewDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


class MonitorController extends AbstractController
{
    public function __construct(private MonitorsService $monitorsService) {}

    #[Route('/monitors', name: 'app_monitors', methods: ['GET'])]
    public function getMonitors(): JsonResponse
    {
        return $this->json($this->monitorsService->getMonitors());
    }


    #[Route('/monitors', name: 'app_post_monitor', methods: ['POST'])]
    public function newMonitor(#[MapRequestPayload( acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_NOT_FOUND )] MonitorNewDTO $monitorNewDTO): JsonResponse
    {   
        $monitorNewEntero=$this->monitorsService->addMonitor($monitorNewDTO);
        // Manejo de errores si el JSON no es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400);
        }
        return $this->json($monitorNewEntero->json());
    }


    #[Route('/monitors/{monitorId}', name: 'app_edit_monitor', methods: ['PUT'])]
    public function updateMonitor(#[MapRequestPayload( acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_NOT_FOUND )] MonitorNewDTO $monitorNewDTO, String $monitorId): JsonResponse
    {   
        $this->monitorsService->updateMonitor($monitorNewDTO, $monitorId);
        // Manejo de errores si el JSON no es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400); 
        }
        return $this->json(['message' => 'Monitor actualizado correctamente']);
    }
    

    #[Route('/monitors/{monitorId}', name: 'app_delete_monitor', methods: ['DELETE'])]
    public function deleteMonitor_by_id(String $monitorId): JsonResponse
    {   
        $this->monitorsService->deleteMonitor($monitorId);
        // Manejo de errores si el JSON no es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400);
        }
        return $this->json(['message' => 'Monitor eliminado correctamente']);
    }
}
