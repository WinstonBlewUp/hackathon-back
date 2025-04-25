<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\CategorieRepository;
use App\Repository\RoomRepository;

use App\DTO\RoomDTO;

#[AsController]
final class CategorieController extends AbstractController
{
    public function __construct(private CategorieRepository $categorieRepository, private RoomRepository $roomRepository){}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $category = $this->categorieRepository->findOneBy(['id' => $id]);

        $data = json_decode($request->getContent(), true);

        $startDate = isset($data['startDate']) ? new \DateTime($data['startDate']) : new \DateTime();
        $endDate = isset($data['endDate']) ? new \DateTime($data['endDate']) : (clone $startDate)->modify('+3 months');

        $rooms = $this->roomRepository->findAvailableRoomsByCategoryAndDates($id, $startDate, $endDate);

        $dtoRooms = array_map(fn($room) => new RoomDTO($room), $rooms);

        return $this->json($dtoRooms, 200, [], ['groups' => 'room:read']);
    } 
}
