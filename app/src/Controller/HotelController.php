<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\HotelService;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

#[Route('/hotel', name: 'hotel_')]
class HotelController extends AbstractController
{
    public function __construct(
        protected HotelService $hotelService
    ) {
    }

    #[Route('/availability', methods: ['POST'], name: 'availability')]
    public function availability(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $hotelRequest = $this->hotelService->validateHotelAvailabilityRequest($request, $entityManager);
            $availability = $this->hotelService->checkHotelAvailability($hotelRequest, $entityManager);
            return $this->json($availability);
        } catch (MissingMandatoryParametersException $e) {
            return $this->json([
                'error' => 'MissingMandatoryParametersException',
                'message' => 'Some mandatory parameters are missing',
                'missing' => $e->getMissingParameters(),
            ], 400);
        } catch (Exception $e) {
            return $this->json([
                'error' => get_class($e),
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
