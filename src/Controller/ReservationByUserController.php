<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

use App\Enum\ReservationEnum;

#[AsController]
final class ReservationByUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        
        $reservations = $user->getReservations()->toArray();

        $reservationsCompleted = array_filter($reservations, function ($reservation) {
            return $reservation->getStatus() === ReservationEnum::COMPLETED;
        });

        return $this->json($reservationsCompleted);
    }
}
