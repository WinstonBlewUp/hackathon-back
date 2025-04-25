<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Repository\RoomRepository;

#[AsController]
final class RoomSearchController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository) {}

    public function __invoke(string $search): JsonResponse
    {

        if (empty($search)) {
            return $this->json(['error' => 'Search term is required'], 400);
        }

        $rooms = $this->roomRepository->searchRoomsByTerm($search);

        $roomsWithHotelDetails = array_map(function ($room) {
            return [
                'roomId' => $room->getId(),
                'roomName' => $room->getName(),
                'roomDescription' => $room->getDescription(),
                'roomBasePrice' => $room->getBasePrice(),
                'roomMaxGuests' => $room->getMaxGuests(),
                'hotelId' => $room->getHotel()->getId(),
                'hotelName' => $room->getHotel()->getName(),
            ];
        }, $rooms);

        return $this->json($roomsWithHotelDetails, 200);
    }
}