<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Categorie;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\DTO\RoomDTO;

#[AsController]
final class CategorieRoomController extends AbstractController
{
    private HotelRepository $hotelRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(HotelRepository $hotelRepository, EntityManagerInterface $entityManager)
    {
        $this->hotelRepository = $hotelRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(int $id): JsonResponse
    {
        // Trouver la catégorie avec l'ID
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], 404);
        }

        // Récupérer tous les hôtels associés à la catégorie
        $hotels = $categorie->getHotels(); // Cette méthode doit exister dans Categorie

        $rooms = [];

        foreach ($hotels as $hotel) {
            foreach ($hotel->getRooms() as $room) {
                $rooms[] = new RoomDTO($room);
            }
        }

        return $this->json($rooms, 200, [], ['groups' => 'room:read']);
    }
}
