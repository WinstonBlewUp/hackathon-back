<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

#[AsController]
#[Route('/api/like', name: 'like_')]
final class LikeController extends AbstractController
{
    public function __construct(private UserRepository $userRepository){}

    #[Route('/{user_id}', name: 'like', methods: ['GET'])]
    public function likedRoom(int $user_id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $user_id]);

        $roomLiked = $user->getLiked();

        return $this->json($roomLiked, 200, [], ['groups' => ['room_like', 'hotel_like']]);
    }
}
