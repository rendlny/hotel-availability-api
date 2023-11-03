<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\RoomFixtures;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class BookingFixtures extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    public $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for ($roomRef = 1; $roomRef <= 21; $roomRef++) {
            $booking = null;

            // for ($i = 1; $i <= 30; $i++) {
            //     if (isset($booking)) {
            //         //to prevent overlapping dates in faked data
            //         $min = Carbon::parse($booking->getEndDate());
            //         $max = Carbon::parse($booking->getEndDate())->addDays(3);
            //         $checkIn = $this->faker->dateTimeBetween($min, $max);
            //     } else {
            //         $checkIn = $this->faker->dateTimeBetween("+1 day", "+3 days");
            //     }

            //     $leastCheckout = Carbon::parse($checkIn)->addDay();
            //     $latestCheckout = Carbon::parse($checkIn)->addWeeks(2);
            //     $checkOut = $this->faker->dateTimeBetween($leastCheckout, $latestCheckout);

            //     $booking = $this->generateBooking($roomRef, $checkIn, $checkOut);

            //     $manager->persist($booking);
            //     $manager->flush();
            // }

            // MAKE ALL ROOMS BOOKED OUT FOR 01-12-2023 -> 10-12-2023
            // TO BE ABLE TO TEST FOR UNAVAILABLE
            $checkIn = Carbon::parse('2023/12/01');
            $checkOut = Carbon::parse('2023/12/10');
            $booking = $this->generateBooking($roomRef, $checkIn, $checkOut);
            $manager->persist($booking);
            $manager->flush();
        }
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
        ];
    }

    public function generateBooking($roomRef, $checkIn, $checkOut)
    {
        $booking = new Booking();
        $booking->setStartDate($checkIn);
        $booking->setEndDate($checkOut);
        $booking->setRoom($this->getReference('room_' . $roomRef));

        $room = $booking->getRoom();
        $peopleCount = $this->faker->numberBetween($room->getBedCount(), $room->getMaxPeople());
        $booking->setPeopleCount($peopleCount);

        return $booking;
    }
}
