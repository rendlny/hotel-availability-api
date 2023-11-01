<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class RoomFixtures extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $reference = 1;
        for ($hotel = 1; $hotel <= 4; $hotel++) {
            for ($i = 1; $i <= 10; $i++) {

                $beds = $faker->numberBetween(1, 4);
                $maxPeople = $faker->numberBetween(1, $beds * 2);

                $room = new Room();
                $room->setName('Room ' . $i);
                $room->setBedCount($beds);
                $room->setMaxPeople($maxPeople);
                $room->setHotel($this->getReference('hotel_' . $hotel));

                $manager->persist($room);
                $manager->flush();

                $this->setReference('room_' . $reference, $room);
                $reference++;
            }
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
