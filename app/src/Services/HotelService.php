<?php

namespace App\Services;

use App\Entity\Hotel;
use Carbon\Carbon;
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
        $query = $queryBuilder
            ->select('r.id', 'r.name', 'r.bed_count as beds', 'r.max_people')
            ->from('App\Entity\Room', 'r')
            ->leftJoin('r.bookings', 'b', 'WITH', $queryBuilder->expr()->eq('r.id', 'b.room_id'))
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('r.hotel_id', ':hotelId'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->isNull('b.id'),
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->gte(':userCheckIn', 'b.end_date'),
                            $queryBuilder->expr()->lte(':userCheckOut', 'b.start_date')
                        )
                    )
                )
            )
            ->setParameter('hotelId', $hotelRequest['hotel_id'])
            ->setParameter('userCheckIn', $hotelRequest['check_in'])
            ->setParameter('userCheckOut', $hotelRequest['check_out'])
            ->groupBy('r.id')
            ->getQuery();


        $availableRooms = $query->getResult();

        return [
            'hotel_id' => $hotelRequest['hotel_id'],
            'check_in' => $hotelRequest['check_in'],
            'check_out' => $hotelRequest['check_out'],
            'status' => count($availableRooms) ? 'available' : 'unavailable',
            'rooms_available' => count($availableRooms),
            'rooms' => $availableRooms
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
        $date = Carbon::createFromFormat('Y-m-d', $input);
        if (!$date) {
            throw new InvalidArgumentException($name . ' must be date format YYYY-MM-DD');
        }

        return $date;
    }
}
