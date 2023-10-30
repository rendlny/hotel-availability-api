<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\RoomFixtures;
use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i = 5; $i++) {
            $booking = new Booking();
            $booking->setPeopleCount(1);
            $booking->setStartDate(new DateTime());
            $booking->setEndDate(new DateTime());
            $booking->setRoom($this->getReference('room_1'));

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
}
