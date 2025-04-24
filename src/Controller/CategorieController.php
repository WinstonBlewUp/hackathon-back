<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\CategorieRepository;
use App\Repository\RoomRepository;

#[AsController]
final class CategorieController extends AbstractController
{
    public function __construct(private CategorieRepository $categorieRepository, private RoomRepository $roomRepository){}

    #[Route('/api/categories/{id}/rooms', name: 'categories_rooms', methods: ['GET'])]
    public function categoryByRoom(int $id): Response
    {
        $category = $this->categorieRepository->findOneBy(['id' => $id]);

        $startDateParam = $request->query->get('startDate');
        $endDateParam = $request->query->get('endDate');

        $startDate = $startDateParam ? new \DateTime($startDateParam) : new \DateTime();
        $endDate = $endDateParam ? new \DateTime($endDateParam) : (clone $startDate)->modify('+3 months');

        $rooms = $this->roomRepository->findAvailableRoomsByCategoryAndDates($id, $startDate, $endDate);

        return $this->json($rooms, 200, [], ['groups' => ['room', 'hotel']]);
    }
}
