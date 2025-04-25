<?php

namespace App\Controller;

use App\Enum\NegociationEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\NegociationRepository;

use App\DTO\NegociationDTO;

#[AsController]
final class NegotiationResponseClientController extends AbstractController
{
    public function __construct(private NegociationRepository $negociationRepository, private EntityManagerInterface $entityManager) {}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $negotiation = $this->negociationRepository->find($id);
        if (!$negotiation) {
            throw new NotFoundHttpException('Negotiation not found');
        }
        $data = json_decode($request->getContent(), true);
        $negotiation->setStatus(NegociationEnum::from($data['status']) ?? $negotiation->getStatus());
        $negotiation->setIsClose($data['isClose'] ?? true);


        $this->entityManager->flush();


        $dto = new NegociationDTO($negociation);

        return $this->json($dto, 200, [], ['groups' => 'negociation:read']);
    }
}
