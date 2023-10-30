<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

// I was trying to recreate how Laravel applies custom request validation
// but didn't work how I need it to
class HotelAvailabilityRequest
{
    #[Type('integer')]
    #[Assert\NotBlank()]
    protected int $hotel_id;

    #[Type('string')]
    #[Assert\NotBlank([])]
    #[Assert\Date(null, 'Invalid date, format must be YYY-MM-DD')]
    protected string $check_in;

    #[Type('string')]
    #[Assert\NotBlank([])]
    #[Assert\Date(null, 'Invalid date, format must be YYY-MM-DD')]
    protected string $check_out;

    public function __construct(int $hotel_id = null, string $check_in = null, string $check_out = null)
    {
        $this->hotel_id = $hotel_id;
        $this->check_in = $check_in;
        $this->check_out = $check_out;
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}
