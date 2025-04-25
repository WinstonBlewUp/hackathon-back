<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\NegociationRepository;
use App\Enum\ReservationEnum;

#[AsController]
final class NegotiationResponseClientAcceptController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private EntityManagerInterface $entityManager) {}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $negotiation = $this->negociationRepository->find($id);
        if (!$negotiation) {
            throw new NotFoundHttpException('Negotiation not found');
        }
        $negotiation->setIsClose(true);
        $reservation = new Reservation();
        $reservation->setCreatedAt(new \DateTimeImmutable());
        $reservation->setStartDate($negotiation->getStartDate());
        $reservation->setEndDate($negotiation->getEndDate());
        $reservation->setPrice($negotiation->getChallengePrice() ? $negotiation->getChallengePrice() : $negotiation->getRequestedPrice());
        $reservation->setStatus(ReservationEnum::CONFIRMED); // ou PENDING selon ta logique
        $reservation->setUser($negotiation->getUser());
        $reservation->setRoom($negotiation->getRoom());

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $this->json($negotiation, 201);
    }
}
