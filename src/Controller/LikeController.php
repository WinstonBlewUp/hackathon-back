<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\UserRepository;

final class LikeController extends AbstractController
{
    public function __construct(private UserRepository $userRepository){}

    #[Route('/api/like/{user_id}', name: 'like', methods: ['GET'])]
    public function likedRoom(int $user_id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $user_id]);

        $roomLiked = $user->getLiked();

        return $this->json($roomLiked, 200, [], ['groups' => ['room', 'hotel']]);
    }
}
