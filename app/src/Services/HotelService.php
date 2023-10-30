<?php

namespace App\Services;

use App\Entity\Hotel;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class HotelService
{
    /**
     * Check Hotel Availability
     * Accepts a Hotel ID, check in-date & check-out date.
     * It will check for availability at the hotel  for those dates and 
     * returns an array which will include status of either
     * "available" or "unavailable"
     * 
     * @param int $hotelId
     * @param DateTime $checkInDate
     * @param DateTime $checkOutDate
     * @return array<string, DateTime|int|string>
     */
    public function checkHotelAvailability(array $hotelRequest): array
    {
        // do availability checks
        return [
            'hotel_id' => $hotelRequest['hotel_id'],
            'check_in' => $hotelRequest['check_in'],
            'check_out' => $hotelRequest['check_out'],
            'status' => 'available',
            //unavailable
        ];
    }

    public function validateHotelAvailabilityRequest(Request $request, $entityManager)
    {
        $hotelId = $request->request->get('hotel_id');
        $startDate = $request->request->get('check_in');
        $endDate = $request->request->get('check_out');

        $missingParams = [];

        if (!isset($hotelId)) {
            $missingParams[] = 'hotel_id';
        }

        if (!isset($startDate)) {
            $missingParams[] = 'check_in';
        }

        if (!isset($endDate)) {
            $missingParams[] = 'check_out';
        }

        if (count($missingParams) > 0) {
            throw new MissingMandatoryParametersException('check_out', $missingParams);
        }

        //validate ID belongs to an existing hotel

        $hotel = $entityManager->getRepository(Hotel::class)->find($hotelId);
        if (!$hotel) {
            throw $this->createNotFoundException('No hotel found for id ' . $hotelId);
        }

        //validate date formats
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);
        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);

        return [
            'hotel_id' => $hotelId,
            'check_in' => $startDate,
            'check_out' => $endDate,
        ];
    }
}
