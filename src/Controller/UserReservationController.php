<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;
use App\Repository\ReservationRepository;

#[AsController]
final class UserReservationController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private ReservationRepository $reservationRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $reservations = $this->reservationRepository->findBy(['user' => $user]);

        return $this->json($reservations);
    }
}
