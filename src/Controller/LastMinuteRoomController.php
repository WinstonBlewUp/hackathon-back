<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\RoomRepository;

#[AsController]
final class LastMinuteRoomController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository){}

    public function __invoke(int $id): JsonResponse
    {
        $now = new \DateTime();
        $tonight = (clone $now)->setTime(0, 0);
        $tomorrow = (clone $tonight)->modify('+1 day');

        $rooms = $this->roomRepository->findAvailableRoomsForTonight($tonight, $tomorrow);

        return $this->json($rooms, 200);
    }
}
