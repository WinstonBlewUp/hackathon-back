<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Repository\NegociationRepository;
use App\Enum\NegociationEnum;

#[AsController]
final class PendingNegociationHotelierController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository) {}

    public function __invoke(): JsonResponse
    {
        $negotiations = $this->negociationRepository->findByStatus(NegociationEnum::PENDING_HOTELIER);

        return $this->json($negotiations);
    }
}
