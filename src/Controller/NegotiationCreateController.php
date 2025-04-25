<?php

namespace App\Controller;

use App\Entity\Negociation;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Enum\NegociationEnum;

#[AsController]
final class NegotiationCreateController
{
    public function __construct(
        private RoomRepository $roomRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $price = $data['requestPrice'] ?? null;
        $roomId = $data['room_id'] ?? null;
        $userId = $data['user_id'] ?? null;
        $startDate = $data['startDate'] ?? null;
        $endDate = $data['endDate'] ?? null;

        if (!$price || !$roomId || !$userId || !$startDate || !$endDate) {
            return new JsonResponse(['error' => 'Champs manquants'], 400);
        }

        $room = $this->roomRepository->find($roomId);
        if (!$room) {
            throw new NotFoundHttpException('Chambre non trouvée');
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException('Utilisateur non trouvé');
        }

        $negotiation = new Negociation();
        $negotiation->setRequestedPrice($price);
        $negotiation->setRoom($room);
        $negotiation->setUser($user);
        $negotiation->setStatus(NegociationEnum::PENDING_HOTELIER);
        $negotiation->setIsClose(false);
        $negotiation->setCreatedAt(new \DateTimeImmutable());
        $negotiation->setResponseAt(new \DateTimeImmutable()); // Initialisé maintenant, pourra être mis à jour plus tard
        $negotiation->setStartDate(new \DateTimeImmutable($startDate));
        $negotiation->setEndDate(new \DateTimeImmutable($endDate));

        $this->em->persist($negotiation);
        $this->em->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [
                'id' => $negotiation->getId(),
                'status' => $negotiation->getStatus()->value,
                'createdAt' => $negotiation->getCreatedAt()->format(DATE_ATOM),
            ]
        ], 201);
    }
}
