<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;

use App\Entity\Room;

#[ApiResource(
    normalizationContext: ['groups' => ['room:read']],
)]

class RoomDTO
{
    #[Groups(['room:read'])]
    public int $roomId;

    #[Groups(['room:read'])]
    public string $roomName;

    #[Groups(['room:read'])]
    public string $roomDescription;

    #[Groups(['room:read'])]
    public float $roomBasePrice;

    #[Groups(['room:read'])]
    public int $roomMaxGuests;

    /** @var string[] */
    #[Groups(['room:read'])]
    public array $roomUsers;

    #[Groups(['room:read'])]
    public int $hotelId;

    #[Groups(['room:read'])]
    public string $hotelName;

    public function __construct(Room $room)
    {
        $this->roomId = $room->getId();
        $this->roomName = $room->getName();
        $this->roomDescription = $room->getDescription();
        $this->roomBasePrice = $room->getBasePrice();
        $this->roomMaxGuests = $room->getMaxGuests();
        $this->roomUsers = array_map(fn($user) => $user->getEmail(), $room->getUsers()->toArray());
        $this->hotelId = $room->getHotel()->getId();
        $this->hotelName = $room->getHotel()->getName();
    }
}