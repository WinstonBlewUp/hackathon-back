<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\ReservationRepository;

#[AsController]
final class RoomAvailableController extends AbstractController
{
    public function __construct(private ReservationRepository $reservationRepository){}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $startDate = isset($data['startDate']) ? new \DateTime($data['startDate']) : null;
        $endDate = isset($data['endDate']) ? new \DateTime($data['endDate']) : null;

        $isAvailable = $this->reservationRepository->isRoomAvailableById($id, $startDate, $endDate);

        return $this->json(['isAvailable' => $isAvailable]);
    }
}
