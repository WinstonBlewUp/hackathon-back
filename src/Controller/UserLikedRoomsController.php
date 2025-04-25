<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\UserRepository;
use App\Repository\RoomRepository;

#[AsController]
final class UserLikedRoomsController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private RoomRepository $roomRepository, private EntityManagerInterface $entityManager){}

    public function __invoke(int $id, int $roomId): JsonResponse
    {
        $user = $this->userRepository->find($id);
        $room = $this->roomRepository->find($roomId);

        if (!$user || !$room) {
            return new JsonResponse(['error' => 'Utilisateur ou chambre introuvable.'], 404);
        }

        $user->addLiked($room);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'La chambre a été ajoutée aux favoris de l’utilisateur.']);
    }
}
