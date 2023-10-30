<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HotelFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $hotels = ['Clayton Hotel', 'Boyne Valley Hotel', 'Beresford Hotel', 'Jacobs Inn Dublin'];
        foreach ($hotels as $index => $hotelName) {
            $hotel = new Hotel();
            $hotel->setName($hotelName);

            $manager->persist($hotel);
            $manager->flush();

            // store reference to Hotel for Room relation to Hotel
            // $this->addReference('hotel-' . $index, $hotel);
        }
    }
}
