<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

use App\DTO\RoomDTO;

#[AsController]
final class LikedRoomByUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $roomLiked = $user->getLiked();

        $roomLikedArray = $roomLiked->toArray();

        $rooms = array_map(fn($room) => new RoomDTO($room), $roomLikedArray);

        return $this->json($rooms, 200, [], ['groups' => 'room:read']);
    }
}
