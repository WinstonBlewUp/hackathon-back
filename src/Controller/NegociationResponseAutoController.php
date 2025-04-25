<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\NegociationRepository;
use App\Repository\HotelRepository;
use App\Enum\NegociationEnum;

#[AsController]
final class NegociationResponseAutoController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private HotelRepository $hotelRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        $negociation = $this->negociationRepository->find($id);

        if (!$negociation) {
            return $this->json(['error' => 'Négociation non trouvée'], 404);
        }

        $room = $negociation->getRoom();
        $hotel = $room?->getHotel();

        if (!$hotel) {
            return $this->json(['error' => 'Hôtel non trouvé'], 404);
        }

        $basePrice = $room->getBasePrice();
        $requestedPrice = $negociation->getRequestedPrice();

        $threshold = $hotel->getThresholds()->filter(function ($seuil) {
            return $seuil->getStartDate() === null && $seuil->getEndDate() === null;
        })->first();
    
        if (!$threshold) {
            return $this->json(['error' => 'Aucun seuil général trouvé pour cet hôtel'], 404);
        }
    
        $seuilMin = $threshold->getMinimum();
        $seuilMax = $threshold->getMaximum();
    
        $minAllowed = $basePrice * ($seuilMin / 100);
        $maxAllowed = $basePrice * ($seuilMax / 100);
    
        $isExactlyMin = $requestedPrice == $minAllowed;
        $isUnderMax = $requestedPrice < $maxAllowed;
        $isBetweenBaseAndMin = $requestedPrice < $basePrice && $requestedPrice > $minAllowed;

        if ($isExactlyMin || $isBetweenBaseAndMin) {
            $negociation->setStatus(NegociationEnum::ACCEPTED_HOTELIER);
        } elseif ($isUnderMax) {
            $negociation->setStatus(NegociationEnum::REFUSED_HOTELIER);
        }

        return $this->json($negociation);

    }
}
