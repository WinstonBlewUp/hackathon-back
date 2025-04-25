<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\NegociationRepository;
use App\Enum\NegociationEnum;

#[AsController]
final class NegotiationResponseHotelController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private EntityManagerInterface $entityManager ) {}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $negociation = $this->negociationRepository->find($id);

        $data = json_decode($request->getContent(), true);

        $challengePrice = $data['challengePrice'] ?? null;
        $isAccepted = $data['isAccepted'] ?? null;

        if ($challengePrice !== null){
            $negociation->setChallengePrice($challengePrice);
            $negociation->setStatus(NegociationEnum::PENDING_CLIENT);
        }

        if ($isAccepted == true){
            $negociation->setStatus(NegociationEnum::PENDING_CLIENT);
        }else if($isAccepted == false){
            $negociation->setStatus(NegociationEnum::REFUSED_HOTELIER);
        }

        $this->entityManager->flush(); 

        return $this->json($negociation);
    }
}
