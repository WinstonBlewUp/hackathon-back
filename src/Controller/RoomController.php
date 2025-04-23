<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\HotelRepository;
use App\Repository\RoomRepository;

#[Route('/api/rooms', name: 'room_')]
final class RoomController extends AbstractController
{
    public function __construct(private HotelRepository $hotelRepository, private RoomRepository $roomRepository, private EntityManagerInterface $entityManager){}

    #[Route('/search/quiz', name: 'search_quiz', methods: ['GET'])]
    public function searchQuiz(Request $request): JsonResponse
    {
        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));

        $maxGuests = $request->query->get('maxGuests');
        
        $criteriaHotel = [
            'children' => $request->query->get('children'),
            'animal' => $request->query->get('animal'),
            'typeCity' => $request->query->get('typeCity'),
            'transport' => $request->query->all('transport'),
            'restoration' => $request->query->get('restoration'),
            'wellness' => $request->query->all('wellness'),
            'business' => $request->query->all('business'),
            'comfort' => $request->query->all('comfort'),
            'addServices' => $request->query->all('addServices'),
            'pmr' => $request->query->get('pmr'),
            'baby' => $request->query->get('baby')
        ];

        $hotels = $this->hotelRepository->findHotelsByCriteria($criteria);

        $rooms = [];
        foreach ($hotels as $hotel) {
            $availableRooms = $roomRepository->findAvailableRoomsForHotel($hotel, $maxGuests, $startDate, $endDate);
            foreach ($availableRooms as $room) {
                $rooms[] = $room;
            }
        }

        return $this->json($rooms);
    }
}
