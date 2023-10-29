<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $bed_count = null;

    #[ORM\Column]
    private ?int $max_people = null;

    #[ORM\Column]
    private ?int $hotel_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBedCount(): ?int
    {
        return $this->bed_count;
    }

    public function setBedCount(int $bed_count): static
    {
        $this->bed_count = $bed_count;

        return $this;
    }

    public function getMaxPeople(): ?int
    {
        return $this->max_people;
    }

    public function setMaxPeople(int $max_people): static
    {
        $this->max_people = $max_people;

        return $this;
    }

    public function getHotelId(): ?int
    {
        return $this->hotel_id;
    }

    public function setHotelId(int $hotel_id): static
    {
        $this->hotel_id = $hotel_id;

        return $this;
    }
}
