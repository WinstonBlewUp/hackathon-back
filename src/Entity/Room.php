<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Controller\SearchQuizController;
use App\Controller\LastMinuteRoomController;
use App\Controller\RecommandationRoomControlerController;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(), 
        new Patch(),
        new Delete(),
        new Get(
            uriTemplate: '/rooms/search/quiz',
            controller: SearchQuizController::class,
            name: 'room_search_quiz',
            normalizationContext: ['groups' => ['room', 'hotel', 'category']],
            denormalizationContext: ['groups' => ['room', 'hotel', 'category']]
        ),
        new Get(
            uriTemplate: '/room/lastminute',
            controller: LastMinuteRoomController::class,
            name: 'room_lastminute',
            normalizationContext: ['groups' => ['room', 'hotel', 'category']],
            denormalizationContext: ['groups' => ['room', 'hotel', 'category']]
        ),
        new Get(
            uriTemplate: '/rooms/{userId}/recommandation',
            controller: RecommandationRoomControlerController::class,
            name: 'room_recommandation',
            normalizationContext: ['groups' => ['room', 'hotel', 'category']],
            denormalizationContext: ['groups' => ['room', 'hotel', 'category']]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ORM\Table(name: 'MTC_ROOM')]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ROOM_ID')]
    #[Groups(['room'])]
    private int $id;

    #[ORM\Column(length: 255, name: 'ROOM_NAME')]
    #[Groups(['room'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, name: 'ROOM_DESCRIPTION')]
    #[Groups(['room'])]
    private ?string $description = null;

    #[ORM\Column(name: 'ROOM_BASE_PRICE')]
    #[Groups(['room'])]
    private ?int $basePrice = null;

    #[ORM\Column(name: 'ROOM_MAX_GUESTS')]
    #[Groups(['room'])]
    private ?int $maxGuests = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'liked')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(name:'HTL_ID',referencedColumnName:'HTL_ID')]
    #[Group(['room'])]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(name:'RES_ID',referencedColumnName:'RES_ID')]
    private ?Reservation $reservation = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBasePrice(): ?int
    {
        return $this->basePrice;
    }

    public function setBasePrice(int $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(int $maxGuests): static
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addLiked($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeLiked($this);
        }

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
