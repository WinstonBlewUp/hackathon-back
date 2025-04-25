<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\ReservationRepository;

#[AsController]
final class NumberOfNightsCompletedByUser extends AbstractController
{

    public function __construct(private ReservationRepository $reservationRepository){}

    public function __invoke(int $id): JsonResponse
    {
        $totalNights = $this->reservationRepository->TotalNightsByUser($id);
        
        return $this->json($totalNights);
    }
}
