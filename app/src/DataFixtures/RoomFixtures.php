<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $room = new Room();
        $room->setName('backend');
        $room->setBedCount(1);
        $room->setMaxPeople(1);

        $manager->persist($room);
        $manager->flush();
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
