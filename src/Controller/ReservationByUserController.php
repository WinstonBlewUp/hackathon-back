<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\UserRepository;

use App\Enum\ReservationEnum;

use App\DTO\ReservationDTO;

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

        $reservationsDTO = array_map(fn($reservation) => new ReservationDTO($reservation), $reservationsCompleted);

        return $this->json($reservationsDTO, 200, [], ['groups' => 'reservation:read']);
    }
}
