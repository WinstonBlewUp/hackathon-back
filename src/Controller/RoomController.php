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
use App\Repository\CategorieRepository;
use App\Repository\ReservationRepository;

#[AsController]
final class RoomController extends AbstractController
{
    public function __construct(private HotelRepository $hotelRepository, private RoomRepository $roomRepository, private EntityManagerInterface $entityManager, private CategorieRepository $categorieRepository, private ReservationRepository $reservationRepository){}

    #[Route('/api/rooms/search/quiz', name: 'room_search_quiz', methods: ['GET'])]
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
            'baby' => $request->query->get('baby'),
            'category' => $request->query->get('category') // récuperer l'id de la categorie
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

    #[Route('api/room/lastminute', name: 'room_lastminute', methods: ['GET'])]
    public function lastMinuteRooms(): JsonResponse
    {
        $now = new \DateTime();
        $tonight = (clone $now)->setTime(0, 0);
        $tomorrow = (clone $tonight)->modify('+1 day');

        $rooms = $this->roomRepository->findAvailableRoomsForTonight($tonight, $tomorrow);

        return $this->json($rooms, 200, [], ['groups' => ['room', 'hotel', 'category']]);
    }

    #[Route('api/rooms/{userId}/recommandation', name: 'room_recommandation', methods: ['GET'])]
    public function recommandationRooms(int $userId): JsonResponse
    {
        $likedCategories = $this->categoryRepository->findTopLikedCategoriesByUser($userId);
        $reservedCategories = $this->categoryRepository->findTopReservedCategoriesByUser($userId);

        $merged = array_merge($likedCategories, $reservedCategories);

        $uniqueCategories = array_map("unserialize", array_unique(array_map("serialize", $merged)));

        $mostFrequentMaxGuests = $this->reservationRepository->findMostFrequentMaxGuestsByUser($userId);

        $recommendedRooms = [];

        foreach ($uniqueCategories as $category) {
            $categoryId = $category->getId();

            $rooms = $this->roomRepository->findAvailableRoomsByCategoryAndUser($categoryId, $userId, $mostFrequentMaxGuests);

            $rooms = array_slice($rooms, 0, 5);

            $recommendedRooms = array_merge($recommendedRooms, $rooms);
        }

        return $this->json($recommendedRooms, 200, [], ['groups' => ['room', 'hotel', 'category']]);
    }
}
