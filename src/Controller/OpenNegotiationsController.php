<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\NegociationRepository;

#[AsController]
final class OpenNegotiationsController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository) {}

    public function __invoke(int $id): JsonResponse
    {
        $openNegotiation = $this->negociationRepository->findOpenNegociationsByUser($id);

        return $this->json($openNegotiation);
    }
}
