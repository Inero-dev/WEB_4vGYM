<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ActivityTypeController extends AbstractController
{
    #[Route('/activity_types', name: 'get_activity_type')]
    public function getActivityTypes(): JsonResponse
    {
        return $this->json([/* array de tipos de actividad */]);

    }
}
