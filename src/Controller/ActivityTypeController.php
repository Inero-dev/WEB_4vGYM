<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\ActivityTypesService;

class ActivityTypeController extends AbstractController
{
    public function __construct(private ActivityTypesService $activityTypeService) {}

    #[Route('/activity_types', name: 'get_activity_type', methods: ['GET'])]
    public function getActivityTypes(): JsonResponse
    {
        return $this->json($this->activityTypeService->getTypes());
    }
}
