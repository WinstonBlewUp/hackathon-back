<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\NegociationRepository;

#[AsController]
final class OpenNegotiationsController extends AbstractController
{
    public function __construct(
        private NegociationRepository $negociationRepository,
        private SerializerInterface $serializer
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $openNegotiation = $this->negociationRepository->findOpenNegociationsByUser($id);
        $openNegotiation = $this->negociationRepository->findOpenNegociationsByUser($id);

        // Transformation des données pour inclure les informations de la chambre et de l'hôtel
        $negotiationsWithRoomDetails = array_map(function ($negociation) {
            $room = $negociation->getRoom();
            $hotel = $room->getHotel();

            return [
                'negociationId' => $negociation->getId(),
                'requestedPrice' => $negociation->getRequestedPrice(),
                'status' => $negociation->getStatus(),
                'createdAt' => $negociation->getCreatedAt()->format('Y-m-d H:i:s'),
                'responseAt' => $negociation->getResponseAt()->format('Y-m-d H:i:s'),
                'challengePrice' => $negociation->getChallengePrice(),
                'isClose' => $negociation->getIsClose(),
                'user' => $negociation->getUser()->getId(),  // Tu peux aussi détailler l'utilisateur ici si nécessaire
                'room' => [
                    'roomId' => $room->getId(),
                    'roomName' => $room->getName(),
                    'roomDescription' => $room->getDescription(),
                    'roomBasePrice' => $room->getBasePrice(),
                    'roomMaxGuests' => $room->getMaxGuests(),
                    'hotel' => [
                        'hotelId' => $hotel->getId(),
                        'hotelName' => $hotel->getName(),
                    ]
                ],
            ];
        }, $openNegotiation);
        return $this->json($negotiationsWithRoomDetails);
    }
}
