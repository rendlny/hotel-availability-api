<?php

namespace App\Services;

use App\Entity\Hotel;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Doctrine\ORM\EntityManagerInterface;

class HotelService
{
    /**
     * Check Hotel Availability
     * Accepts a Hotel ID, check in-date & check-out date.
     * It will check for availability at the hotel  for those dates and 
     * returns an array which will include status of either
     * "available" or "unavailable"
     * 
     * @param array<string, DateTime|int|string> $hotelRequest
     * @param EntityManagerInterface $entityManager
     * @return array<string, mixed>
     */
    public function checkHotelAvailability(array $hotelRequest, EntityManagerInterface $entityManager): array
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $bookedOutRooms = $queryBuilder
            ->select('b.room_id')
            ->from('App\Entity\Booking', 'b')
            ->leftJoin('b.room', 'r', 'WITH', $queryBuilder->expr()->eq('r.id', 'b.room_id'))
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('r.hotel_id', ':hotelId'),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->lt(':userCheckIn', 'b.end_date'),
                        $queryBuilder->expr()->gte(':userCheckOut', 'b.start_date')
                    )

                )
            )
            ->setParameter('hotelId', $hotelRequest['hotel_id'])
            ->setParameter('userCheckIn', $hotelRequest['check_in'])
            ->setParameter('userCheckOut', $hotelRequest['check_out'])
            ->groupBy('b.room_id')
            ->getQuery()
            ->getResult();

        $bookedOutRoomIds = array_column($bookedOutRooms, 'room_id');

        $queryBuilder = $entityManager->createQueryBuilder();
        if (count($bookedOutRoomIds) == 0) {
            $query = $queryBuilder
                ->select('r.id', 'r.name', 'r.bed_count as beds', 'r.max_people')
                ->from('App\Entity\Room', 'r')
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('r.hotel_id', ':hotelId')
                    )
                )
                ->setParameter('hotelId', $hotelRequest['hotel_id'])
                ->getQuery();
        } else {
            $query = $queryBuilder
                ->select('r.id', 'r.name', 'r.bed_count as beds', 'r.max_people')
                ->from('App\Entity\Room', 'r')
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->notIn('r.id', ':bookedOutRoomIds'),
                        $queryBuilder->expr()->eq('r.hotel_id', ':hotelId')
                    )
                )
                ->setParameter('hotelId', $hotelRequest['hotel_id'])
                ->setParameter('bookedOutRoomIds', $bookedOutRoomIds)
                ->getQuery();
        }

        $availableRooms = $query->getResult();

        return [
            'hotel_id' => $hotelRequest['hotel_id'],
            'check_in' => $hotelRequest['check_in'],
            'check_out' => $hotelRequest['check_out'],
            'status' => count($availableRooms) ? 'available' : 'unavailable',
            'rooms_available' => count($availableRooms),
            'rooms' => $availableRooms,
        ];
    }

    /**
     * Validate Hotel Availability Request
     * 
     * Validating request attributes are the expected formats and
     * returns an array of the validated attributes
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return array<string, DateTime|int|string> validated and cleaned request
     * @throws MissingMandatoryParametersException
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     */
    public function validateHotelAvailabilityRequest(Request $request, EntityManagerInterface $entityManager): array
    {
        /**
         * NOTE:
         * All of this validation could be applied through one request file in Laravel
         * I tried creating the app/src/Requests/HotelAvailabilityRequest and 
         * using the ValidatorInterface for a similar approach but I couldn't 
         * get the errors to format nicely
         */
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

        if (!ctype_digit($hotelId)) {
            throw new InvalidArgumentException('hotel_id must be an integer');
        }

        $hotel = $entityManager->getRepository(Hotel::class)->find($hotelId);
        if (!$hotel) {
            throw new NotFoundHttpException('No Hotel found for ID ' . $hotelId);
        }

        $startDate = $this->formatDateString((string) $startDate, 'check_in');
        $endDate = $this->formatDateString((string) $endDate, 'check_out');

        if ($startDate == $endDate) {
            throw new InvalidArgumentException('check_in and check_out cannot be the same date');
        }

        if ($startDate > $endDate) {
            throw new InvalidArgumentException('The check_in date cannot be ahead of the check_out date');
        }

        if ($startDate <= Carbon::now()->format('Y-m-d')) {
            throw new InvalidArgumentException('The check_in date must be a date in the future');
        }

        return [
            'hotel_id' => $hotelId,
            'check_in' => $startDate->toDateString(),
            'check_out' => $endDate->toDateString(),
        ];
    }

    /**
     * Format Date String
     * @param string $input string date
     * @param string $name name of field that will be shown in error if format fails
     * @return Carbon Carbon format date
     * @throws InvalidArgumentException
     */
    function formatDateString($input, $name): Carbon
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $input);
            return $date;
        } catch (InvalidFormatException $e) {
            throw new InvalidArgumentException($name . ' must be date format YYYY-MM-DD');
        }
    }
}
