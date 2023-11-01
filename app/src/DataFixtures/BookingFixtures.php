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
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($roomRef = 1; $roomRef <= 40; $roomRef++) {
            for ($i = 1; $i <= 5; $i++) {

                $checkIn = $faker->dateTimeBetween("+1 day", "+2 months");
                $leastCheckout = Carbon::parse($checkIn)->addDay();
                $latestCheckout = Carbon::parse($checkIn)->addWeeks(2);
                $checkOut = $faker->dateTimeBetween($leastCheckout, $latestCheckout);

                $booking = new Booking();
                $booking->setStartDate($checkIn);
                $booking->setEndDate($checkOut);
                $booking->setRoom($this->getReference('room_' . $roomRef));

                $room = $booking->getRoom();
                $peopleCount = $faker->numberBetween($room->getBedCount(), $room->getMaxPeople());
                $booking->setPeopleCount($peopleCount);

                $manager->persist($booking);
                $manager->flush();
            }
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
}
