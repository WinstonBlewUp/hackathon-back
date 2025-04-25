<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Controller\NumberOfNightsCompletedByUser;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\ReservationEnum;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(), 
        new Patch(),
        new Delete(),
        new Get(
            uriTemplate: '/reservation/{id}/totalNights',
            controller: NumberOfNightsCompletedByUser::class,
            name: 'totalNights')
    ]
)]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'MTC_RESERVATION')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'RES_ID')]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'RES_CREATED_AT')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'RES_START_DATE')]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'RES_END_DATE')]
    private \DateTimeImmutable $endDate;

    #[ORM\Column(name: 'RES_PRICE')]
    private int $price;

    #[ORM\Column(name: 'RES_STATUS', enumType: ReservationEnum::class)]
    private ReservationEnum $status;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name:'USR_ID',referencedColumnName:'USR_ID')]
    private User $user;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name:'ROOM_ID',referencedColumnName:'ROOM_ID')]
    private ?Room $room = null;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ReservationEnum
    {
        return $this->status;
    }

    public function setStatus(ReservationEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }
}
