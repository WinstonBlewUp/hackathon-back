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
final class NegotiationRoomAvailableController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private EntityManagerInterface $entityManager ) {}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $negociation = $this->negociationRepository->find($id);

        $isRoomAvailable = $this->negociationRepository->isRoomReservedDuringNegotiation($negociation);

        if($isRoomAvailable == false){
            $negociation->setStatus(NegociationEnum::REFUSED_NO_DISP);
        }

        $this->entityManager->flush(); 

        return $this->json($negociation);
    }
}
