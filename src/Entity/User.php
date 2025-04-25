<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use App\Controller\LikedRoomByUserController;
use App\Controller\UserExistController;
use App\Controller\AverageSavingsPercentageController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(), 
        new Patch(),
        new Delete(),
        new Get(
            uriTemplate: '/like/{id}',
            controller: LikedRoomByUserController::class,
            name: 'get_user_likes'
        ),
        new Get(
            uriTemplate: '/exist/{email}/{password}',
            controller: UserExistController::class,
            name: 'user_exist'),
        new Get(
            uriTemplate: 'user/{id}/averageSavingsPercentage',
            controller: AverageSavingsPercentageController::class,
            read: false,
            name: 'average_savings_percentage'
        ),
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'MTC_USER')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'USR_ID')]
    #[Groups(['room_like', 'hotel_like'])]
    private int $id;

    #[ORM\Column(length: 30, name: 'USR_NAME')]
    #[Groups(['room_like', 'hotel_like'])]
    private string $name;

    #[ORM\Column(length: 100, name: 'USR_FIRSTNAME')]
    #[Groups(['room_like', 'hotel_like'])]
    private string $firstname;

    #[ORM\Column(length: 255, name: 'USR_EMAIL')]
    #[Groups(['room_like', 'hotel_like'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, name: 'USR_PASSWORD')]
    private ?string $password = null;

    #[ORM\Column(length: 255, name: 'USR_RESET_TOKEN')]
    private ?string $resetToken = null;

    #[ORM\Column(name : 'USR_ROLE')]
    private array $role = [];

    #[ORM\Column(name : 'USR_CREATED_AT')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'MTC_LIKE')]
    #[ORM\JoinColumn(name: 'USR_ID', referencedColumnName: 'USR_ID', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'ROOM_ID', referencedColumnName: 'ROOM_ID')]
    #[Groups(['room_like'])]
    private Collection $liked;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'user')]
    private Collection $payments;

    /**
     * @var Collection<int, Negociation>
     */
    #[ORM\OneToMany(targetEntity: Negociation::class, mappedBy: 'user')]
    private Collection $negociations;

    /**
     * @var Collection<int, Hotel>
     */
    #[ORM\OneToMany(targetEntity: Hotel::class, mappedBy: 'user')]
    private Collection $hotels;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'user')]
    private Collection $reservations;

    public function __construct()
    {
        $this->liked = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->negociations = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getLiked(): Collection
    {
        return $this->liked;
    }

    public function addLiked(Room $liked): static
    {
        if (!$this->liked->contains($liked)) {
            $this->liked->add($liked);
        }

        return $this;
    }

    public function removeLiked(Room $liked): static
    {
        $this->liked->removeElement($liked);

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setUser($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUser() === $this) {
                $payment->setUser(null);
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
            $negociation->setUser($this);
        }

        return $this;
    }

    public function removeNegociation(Negociation $negociation): static
    {
        if ($this->negociations->removeElement($negociation)) {
            // set the owning side to null (unless already changed)
            if ($negociation->getUser() === $this) {
                $negociation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): static
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
            $hotel->setUser($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getUser() === $this) {
                $hotel->setUser(null);
            }
        }

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
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }
}
