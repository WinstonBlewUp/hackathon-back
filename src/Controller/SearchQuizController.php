<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Repository\RoomRepository;

#[AsController]
final class SearchQuizController extends AbstractController
{
    public function __construct(private HotelRepository $hotelRepository, private RoomRepository $roomRepository){}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $startDate = isset($data['startDate']) ? new \DateTime($data['startDate']) : null;
        $endDate = isset($data['endDate']) ? new \DateTime($data['endDate']) : null;


        $maxGuests = (int) ($data['maxGuests'] ?? 0);
        
        $criteriaHotel = [
            'children' => $data['children'] ?? null,
            'animal' => $data['animal'] ?? null,
            'typeCity' => $data['typeCity'] ?? null,
            'transport' => $data['transport'] ?? [],
            'restoration' => $data['restoration'] ?? null,
            'wellness' => $data['wellness'] ?? [],
            'business' => $data['business'] ?? [],
            'comfort' => $data['comfort'] ?? [],
            'addServices' => $data['addServices'] ?? [],
            'pmr' => $data['pmr'] ?? null,
            'baby' => $data['baby'] ?? null,
            'category' => $data['category'] ?? null,
        ];

        $hotels = $this->hotelRepository->findHotelByCriteria($criteriaHotel);

        $rooms = [];
        foreach ($hotels as $hotel) {
            $availableRooms = $this->roomRepository->findAvailableRoomsForHotel($hotel, $maxGuests, $startDate, $endDate);
            foreach ($availableRooms as $room) {
                $rooms[] = $room;
            }
        }

        return $this->json($rooms, 200);
    }
}
