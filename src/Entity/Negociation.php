<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\NegociationRepository;
use App\Controller\AverageSavingsPercentageController;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\NegociationEnum;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(), 
        new Patch(),
        new Delete(),
        new Get(
            uriTemplate: '/averageSavingsPercentage/{id}',
            controller: AverageSavingsPercentageController::class,
            name: 'average_savings_percentage'
        )
    ]
)]
#[ORM\Entity(repositoryClass: NegociationRepository::class)]
#[ORM\Table(name: 'MTC_NEGOCIATION')]
class Negociation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'NEG_ID')]
    private int $id;

    #[ORM\Column(name: 'NEG_REQUESTED_PRICE')]
    private int $requestedPrice;

    #[ORM\Column(name: 'NEG_STATUS', enumType: NegociationEnum::class)]
    private NegociationEnum $status;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, name: 'NEG_CREATED_AT')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, name: 'NEG_RESPONSE_AT')]
    private \DateTimeImmutable $responseAt;

    #[ORM\Column(length: 255, nullable: true, name: 'NEG_CHALLENGE_PRICE')]
    private ?int $challengePrice = null;

    #[ORM\ManyToOne(inversedBy: 'negociations')]
    #[ORM\JoinColumn(name:'USR_ID',referencedColumnName:'USR_ID')]
    private ?User $user = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRequestedPrice(): int
    {
        return $this->requestedPrice;
    }

    public function setRequestedPrice(int $requestedPrice): static
    {
        $this->requestedPrice = $requestedPrice;

        return $this;
    }

    public function getStatus(): NegociationEnum
    {
        return $this->status;
    }

    public function setStatus(NegociationEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getResponseAt(): \DateTimeImmutable
    {
        return $this->responseAt;
    }

    public function setResponseAt(\DateTimeImmutable $responseAt): static
    {
        $this->responseAt = $responseAt;

        return $this;
    }

    public function getChallengePrice(): ?int
    {
        return $this->challengePrice;
    }

    public function setChallengePrice(?int $challengePrice): static
    {
        $this->challengePrice = $challengePrice;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
