<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\ActivityTypesService; //no reconoce la clase ActivityTypesService

class ActivityTypeController extends AbstractController
{
    public function __construct(ActivityTypesService $activityTypeService) {} //el error salta aquí

    #[Route('/activity_types', name: 'get_activity_type')]
    public function getActivityTypes(): JsonResponse
    {
        return $this->json([$activityTypeService->getTypes()]);
    }
}
