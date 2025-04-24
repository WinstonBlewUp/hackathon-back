<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

#[AsController]
final class LikedRoomByUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository){}

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
            
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        
        $roomLiked = $user->getLiked();
        
        return $this->json($roomLiked, 200, [], ['groups' => ['room_like', 'hotel_like']]);
    }
}
