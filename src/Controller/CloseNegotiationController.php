<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\NegociationRepository;

#[AsController]
final class CloseNegotiationController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private EntityManagerInterface $entityManager ) {}

    public function __invoke(int $id): JsonResponse
    {
        $negociation = $this->negociationRepository->find($id);

        $negociation->setIsClose(true);

        $this->entityManager->flush();

        return $this->json($negociation);
    }
}
