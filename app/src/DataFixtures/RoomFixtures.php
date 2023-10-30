<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RoomFixtures extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {
            $room = new Room();
            $room->setName('Room ' . $i);
            $room->setBedCount(1);
            $room->setMaxPeople(1);
            $room->setHotel($this->getReference('hotel_1'));

            $manager->persist($room);
            $manager->flush();

            $this->addReference('room_' . $i, $room);
        }
    }

    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            HotelFixtures::class,
        ];
    }
}
