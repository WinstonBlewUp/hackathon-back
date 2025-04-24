<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/like', name: 'like')]
final class LikeController extends AbstractController
{
    #[Route('/', name: '')]
    public function likedRoom(): JsonResponse
    {
        $user = $this->getUser();

        $roomLiked = $user->getLiked();

        return $this->json($roomLiked, 200, [], ['groups' => ['room_like', 'hotel_like']]);
    }
}
