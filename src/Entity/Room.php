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
use App\Controller\RoomAvailableController;
use App\Controller\RoomSearchController;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(), 
        new Patch(),
        new Delete(),
        new Post(
            uriTemplate: '/rooms/search/quiz',
            controller: SearchQuizController::class,
            name: 'room_search_quiz',
            normalizationContext: ['groups' => ['room', 'hotel', 'category']],
            denormalizationContext: ['groups' => ['room', 'hotel', 'category']]
        ),
        new Post(
            uriTemplate: '/rooms/available/{id}',
            controller: RoomAvailableController::class,
            name: 'room_available'
        ),
        new GetCollection(
            uriTemplate: '/room/lastminute',
            controller: LastMinuteRoomController::class,
            name: 'room_lastminute'
        ),
        new Get(
            uriTemplate: '/rooms/{id}/recommandation',
            controller: RecommandationRoomControlerController::class,
            name: 'room_recommandation',
            normalizationContext: ['groups' => ['room', 'hotel', 'category']],
            denormalizationContext: ['groups' => ['room', 'hotel', 'category']]
        ),
        new Get(
            uriTemplate: '/rooms/search',
            controller: RoomSearchController::class,
            read: false,
            name: 'room_search',
            normalizationContext: ['groups' => ['room', 'hotel']]
        )
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

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'room')]
    private Collection $reservations;

    /**
     * @var Collection<int, Negociation>
     */
    #[ORM\OneToMany(targetEntity: Negociation::class, mappedBy: 'room')]
    private Collection $negociations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->negociations = new ArrayCollection();
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

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRoom() === $this) {
                $reservation->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Negociation>
     */
    public function getNegociations(): Collection
    {
        return $this->negociations;
    }

    public function addNegociation(Negociation $negociation): static
    {
        if (!$this->negociations->contains($negociation)) {
            $this->negociations->add($negociation);
            $negociation->setRoom($this);
        }

        return $this;
    }

    public function removeNegociation(Negociation $negociation): static
    {
        if ($this->negociations->removeElement($negociation)) {
            // set the owning side to null (unless already changed)
            if ($negociation->getRoom() === $this) {
                $negociation->setRoom(null);
            }
        }

        return $this;
    }
}
