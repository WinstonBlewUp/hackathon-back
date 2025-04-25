<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Repository\NegociationRepository;
use App\Enum\NegociationEnum;

#[AsController]
final class AcceptNegoController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private ReservationRepository $reservationRepository, private NegociationRepository $negociationRepository) {}

    public function __invoke(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $negociation = $this->negociationRepository->findOneBy(['id' => $id]);

        $negociation->setStatus(NegociationEnum::ACCEPTED_HOTELIER);

        $entityManager->flush();

        return $this->json(['message' => 'Statut de la négociation mis à jour'], 200);
    }
}
