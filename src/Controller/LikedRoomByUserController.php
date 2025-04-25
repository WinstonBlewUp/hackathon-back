<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

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

        $roomLikedWithHotelDetails = array_map(function ($room) {
            return [
                'roomId' => $room->getId(),
                'roomName' => $room->getName(),
                'roomDescription' => $room->getDescription(),
                'roomBasePrice' => $room->getBasePrice(),
                'roomMaxGuests' => $room->getMaxGuests(),
                'hotelId' => $room->getHotel()->getId(),
                'hotelName' => $room->getHotel()->getName(),
            ];
        }, $roomLikedArray);

        return $this->json($roomLikedWithHotelDetails, 200);
    }
}
