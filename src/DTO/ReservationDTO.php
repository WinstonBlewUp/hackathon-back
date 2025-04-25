<?php

namespace App\DTO;

use App\Entity\Reservation;
use Symfony\Component\Serializer\Annotation\Groups;

class ReservationDTO
{
    #[Groups(['reservation:read'])]
    public int $reservationId;

    #[Groups(['reservation:read'])]
    public string $createdAt;

    #[Groups(['reservation:read'])]
    public string $startDate;

    #[Groups(['reservation:read'])]
    public string $endDate;

    #[Groups(['reservation:read'])]
    public int $price;

    #[Groups(['reservation:read'])]
    public string $status;

    #[Groups(['reservation:read'])]
    public int $user;

    #[Groups(['reservation:read'])]
    public array $room;

    public function __construct(Reservation $reservation)
    {
        $room = $reservation->getRoom();
        $hotel = $room?->getHotel();

        $this->reservationId = $reservation->getId();
        $this->createdAt = $reservation->getCreatedAt()->format('Y-m-d H:i:s');
        $this->startDate = $reservation->getStartDate()->format('Y-m-d');
        $this->endDate = $reservation->getEndDate()->format('Y-m-d');
        $this->price = $reservation->getPrice();
        $this->status = $reservation->getStatus()->value;
        $this->user = $reservation->getUser()?->getId();

        $this->room = [
            'roomId' => $room?->getId(),
            'roomName' => $room?->getName(),
            'roomDescription' => $room?->getDescription(),
            'roomBasePrice' => $room?->getBasePrice(),
            'roomMaxGuests' => $room?->getMaxGuests(),
            'hotelId' => $hotel?->getId(),
            'hotelName' => $hotel?->getName(),
        ];
    }
}
