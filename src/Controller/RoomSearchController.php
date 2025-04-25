<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Repository\RoomRepository;

#[AsController]
final class RoomSearchController extends AbstractController
{
    public function __construct(private RoomRepository $roomRepository) {}

    public function __invoke(Request $request): JsonResponse
    {
        $search = $request->query->get('search');

        if (empty($search)) {
            return $this->json(['error' => 'Search term is required'], 400);
        }

        $rooms = $this->roomRepository->searchRoomsByTerm($search);

        return $this->json($rooms, 200, [], ['groups' => ['room', 'hotel']]);
    }
}