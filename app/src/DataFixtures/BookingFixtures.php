<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\RoomFixtures;
use DateTime;

class BookingFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $booking = new Booking();
        $booking->setPeopleCount(1);
        $booking->setStartDate(new DateTime());
        $booking->setEndDate(new DateTime());

        $manager->persist($booking);
        $manager->flush();
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
