<?php

namespace App\Services;

class HotelService
{
    public function checkHotelAvailability(int $hotelId, $checkInDate, $checkOutDate): array
    {
        // do availability checks
        return [
            'hotel_id' => $hotelId,
            'check_in' => $checkInDate,
            'check_out' => $checkOutDate,
            'status' => 'available', //unavailable
        ];
    }
}
