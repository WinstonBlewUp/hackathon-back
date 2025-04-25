<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Repository\NegociationRepository;

#[AsController]
final class AverageSavingsPercentageController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        // Récupérer les négociations acceptées pour l'utilisateur
        $negociations = $this->negociationRepository->findAcceptedNegociationsByUserId($id);

        if (empty($negociations)) {
            return new JsonResponse(['error' => 'No accepted negotiations found for this user'], 404);
        }

        $totalDifference = 0;
        $count = 0;

        foreach ($negociations as $negociation) {
            $requestedPrice = $negociation->getRequestedPrice();
            $challengePrice = $negociation->getChallengePrice();

            if ($challengePrice !== null) {
                $difference = $requestedPrice - $challengePrice;
                $totalDifference += $difference;
                $count++;
            }
        }

        if ($count === 0) {
            return new JsonResponse(['error' => 'No valid challenge prices found'], 400);
        }

        $averageDifference = $totalDifference / $count;

        return new JsonResponse(['average_savings' => round($averageDifference, 2)]);
    }
}
