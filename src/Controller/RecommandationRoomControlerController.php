<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\RoomRepository;
use App\Repository\CategorieRepository;
use App\Repository\ReservationRepository;

#[AsController]
final class RecommandationRoomControlerController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository, private CategorieRepository $categorieRepository, private ReservationRepository $reservationRepository){}

    public function __invoke(int $id): JsonResponse
    {
        $likedCategories = $this->categorieRepository->findTopLikedCategoriesByUser($id);
        $reservedCategories = $this->categorieRepository->findTopReservedCategoriesByUser($id);

        $merged = array_merge($likedCategories, $reservedCategories);

        $uniqueCategories = array_map("unserialize", array_unique(array_map("serialize", $merged)));

        $mostFrequentMaxGuests = $this->reservationRepository->findMostFrequentMaxGuestsByUser($id);

        $recommendedRooms = [];

        foreach ($uniqueCategories as $category) {
            $categoryId = $category->getId();

            $rooms = $this->roomRepository->findAvailableRoomsByCategoryAndUser($categoryId, $id, $mostFrequentMaxGuests);

            $rooms = array_slice($rooms, 0, 5);

            $recommendedRooms = array_merge($recommendedRooms, $rooms);
        }

        return $this->json($recommendedRooms, 200, [], ['groups' => ['room', 'hotel', 'category']]);
    }
}
