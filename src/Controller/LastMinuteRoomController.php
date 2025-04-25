<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\RoomRepository;

use App\DTO\RoomDTO;

#[AsController]
final class LastMinuteRoomController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository){}

    public function __invoke(): JsonResponse
    {
        $now = new \DateTime();
        $tonight = (clone $now)->setTime(0, 0);
        $tomorrow = (clone $tonight)->modify('+1 day');

        $rooms = $this->roomRepository->findAvailableRoomsForTonight($tonight, $tomorrow);

        $dtoRooms = array_map(fn($room) => new RoomDTO($room), $rooms);

        return $this->json($dtoRooms, 200, [], ['groups' => 'room:read']);
    }
}
