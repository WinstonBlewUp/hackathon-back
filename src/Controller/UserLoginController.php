<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

#[AsController]
final class UserLoginController extends AbstractController
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(string $email, string $password): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $email, 'password' => $password]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        return $this->json($user);
    }
}
