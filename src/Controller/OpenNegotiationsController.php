<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\NegociationRepository;

use App\DTO\NegociationDTO;

#[AsController]
final class OpenNegotiationsController extends AbstractController
{
    public function __construct(
        private NegociationRepository $negociationRepository,
        private SerializerInterface $serializer
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $openNegotiation = $this->negociationRepository->findOpenNegociationsByUser($id);
        
        $negotiationsWithRoomDetails = array_map(fn($negociation) => new NegociationDTO($negociation), $openNegotiation);

        return $this->json($negotiationsWithRoomDetails, 200, [], ['groups' => 'negociation:read']);
    }
}
