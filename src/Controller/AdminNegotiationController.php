<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\HotelRepository;
use App\Repository\NegociationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class AdminNegotiationController extends AbstractController
{
    public function __construct(
        private NegociationRepository $negotiationRepository,
        private HotelRepository $hotelRepository,
        private UserRepository $userRepository
    ) {}

    private function findHotelsForUser(User $user)
    {
        return $this->hotelRepository->findByUser($user);
    }

    private function findPendingNegotiationsForUser(User $user)
    {
        $hotels = $this->findHotelsForUser($user);
        $pendingNegotiations = [];

        foreach ($hotels as $hotel) {
            $negotiations = $this->negotiationRepository->findPendingNegotiationsByHotel($hotel);
            foreach ($negotiations as $negotiation) {
                $pendingNegotiations[] = $negotiation;
            }
        }

        return $pendingNegotiations;
    }

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $openNegotiations = $this->findPendingNegotiationsForUser($user);

        $data = array_map(function ($negotiation) {
            $room = $negotiation->getRoom();
            $hotel = $room->getHotel();

            return [
                'id' => $negotiation->getId(),
                'requestedPrice' => $negotiation->getRequestedPrice(),
                'status' => $negotiation->getStatus()->value ?? $negotiation->getStatus(),
                'createdAt' => $negotiation->getCreatedAt()?->format('c'),
                'responseAt' => $negotiation->getResponseAt()?->format('c'),
                'challengePrice' => $negotiation->getChallengePrice(),
                'isClose' => $negotiation->getIsClose(),
                'user' => '/api/users/' . $negotiation->getUser()?->getId(),
                'room' => [
                    'roomId' => $room->getId(),
                    'roomName' => $room->getName(),
                    'roomDescription' => $room->getDescription(),
                    'roomBasePrice' => $room->getBasePrice(),
                    'roomMaxGuests' => $room->getMaxGuests(),
                    'roomUsers' => array_map(
                        fn($user) => '/api/users/' . $user->getId(),
                        $room->getUsers()->toArray()
                    ),
                    'hotelId' => $hotel->getId(),
                    'hotelName' => $hotel->getName(),
                ]
            ];
        }, $openNegotiations);

        return $this->json($data);
    }
}
