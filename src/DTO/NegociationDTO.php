<?php

namespace App\DTO;

use App\Entity\Negociation;
use Symfony\Component\Serializer\Annotation\Groups;

class NegociationDTO
{
    #[Groups(['negociation:read'])]
    public int $negociationId;

    #[Groups(['negociation:read'])]
    public int $requestedPrice;

    #[Groups(['negociation:read'])]
    public string $status;

    #[Groups(['negociation:read'])]
    public string $createdAt;

    #[Groups(['negociation:read'])]
    public string $responseAt;

    #[Groups(['negociation:read'])]
    public ?int $challengePrice;

    #[Groups(['negociation:read'])]
    public bool $isClose;

    #[Groups(['negociation:read'])]
    public int $user;

    #[Groups(['negociation:read'])]
    public array $room;

    public function __construct(Negociation $negociation)
    {
        $room = $negociation->getRoom();
        $hotel = $room?->getHotel();

        $this->negociationId = $negociation->getId();
        $this->requestedPrice = $negociation->getRequestedPrice();
        $this->status = $negociation->getStatus()->value;
        $this->createdAt = $negociation->getCreatedAt()->format('Y-m-d H:i:s');
        $this->responseAt = $negociation->getResponseAt()->format('Y-m-d H:i:s');
        $this->challengePrice = $negociation->getChallengePrice();
        $this->isClose = $negociation->getIsClose();
        $this->user = $negociation->getUser()?->getId();

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
