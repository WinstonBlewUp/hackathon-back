<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\RoomRepository;

use App\DTO\RoomDTO;

final class AllRoomsController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository) {}

    public function __invoke(): JsonResponse
    {
        $rooms = $this->roomRepository->findAll();

        $dtoList = array_map(fn($room) => new RoomDTO($room), $rooms);

        return $this->json($dtoList, 200, [], ['groups' => 'room:read']);
    }
}
