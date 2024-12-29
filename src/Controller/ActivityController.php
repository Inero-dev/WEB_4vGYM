<?php

namespace App\Controller;
use App\Services\ActivityService;
use App\Models\ActivityNewDTO;
use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;


class ActivityController extends AbstractController
{
    public function __construct(private ActivityService $activityService    ) {}

    #[Route('/list_activities', name: 'app_activities', methods: ['GET'])]
    //función pasar una fecha, controlar si la fecha es correcta o no, si es correcta pasarla al servicio
    public function getActivities(?string $date, LoggerInterface $logger): JsonResponse 
    {
        $dateObject = $date ? \DateTime::createFromFormat('d-m-Y', $date) : null; // si es true, convierte a Fecha

        if ($date && !$dateObject) {//si $date tiene un valor (no nulo ni vacío) y $dateObject es null
            $logger->error('Formato de fecha inválido: ' . $date);
            return $this->json(['error' => 'Formato de fecha inválido. Use dd-MM-yyyy.'], 400);
        }

        // Obtener las actividades (pasando la fecha o nula)
        return $this->json($this->activityService->getListActivities($dateObject));
    }

    #[Route('/add_activity', name: 'app_post_activity', methods: ['POST'])]
    public function addActivity(#[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_BAD_REQUEST)] ActivityNewDTO $activityNewDTO): JsonResponse
    {
        // Llamamos al servicio para agregar la actividad con el DTO
        $createdActivity = $this->activityService->addActivity($activityNewDTO);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400);
        }

        // Si la actividad fue creada correctamente, se devuelve como JSON
        return $this->json($createdActivity);
    }


    #[Route('/list_activities/{activityId}', name: 'app_edit_activity', methods: ['PUT'])]
    public function updateActivity(#[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_NOT_FOUND)] ActivityNewDTO $activityNewDTO, String $activityId): JsonResponse
    {
        $this->activityService->updateActivity($activityNewDTO, $activityId);
        // Manejo de errores si el JSON no es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400);
        }
        return $this->json(['message' => 'Activity actualizado correctamente']);
    }

    #[Route('/list_activities/{activityId}', name: 'app_delete_activity', methods: ['DELETE'])]
    public function deleteActivity_by_id(String $activityId): JsonResponse
    {
        $this->activityService->deleteActivity($activityId);
        // Manejo de errores si el JSON no es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'JSON inválido'], 400);
        }
        return $this->json(['message' => 'Activity eliminado correctamente']);
    }
     
}
