<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\HotelService;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HotelController extends AbstractController
{
    public function __construct(
        protected HotelService $service
    ) {
    }

    #[Route('/api/hotel/availability', methods: ['POST'], name: 'app_hotel_availability')]
    public function availability(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $hotelRequest = $this->service->validateHotelAvailabilityRequest($request, $entityManager);
            $availability = $this->service->checkHotelAvailability($hotelRequest);
            return $this->json($availability);
        } catch (MissingMandatoryParametersException $e) {
            return $this->json([
                'error' => 'Some mandatory parameters are missing',
                'missing' => $e->getMissingParameters(),
            ], 400);
        } catch (NotFoundHttpException $e) {
            return $this->json([
                'error' => 'Not Found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
