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
use Symfony\Component\Serializer\SerializerInterface;

class ActivityController extends AbstractController
{
    public function __construct(private ActivityService $activityService    ) {}

    #[Route('/list_activities', name: 'app_activities', methods: ['GET'])]
    public function getActivities(): JsonResponse
    {
        return $this->json($this->activityService->getListActivities());
    }


    //  agregar una nueva actividad
    #[Route('/add_activity', name: 'app_post_activity', methods: ['POST'])]
    public function addActivity(#[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_BAD_REQUEST)] ActivityNewDTO $activityNewDTO): JsonResponse
    {
        // Llamamos al servicio para agregar la actividad con el DTO
        $createdActivity = $this->activityService->addActivity($activityNewDTO);

        // Si la actividad fue creada correctamente, se devuelve como JSON
        return $this->json($createdActivity);
    }
}
