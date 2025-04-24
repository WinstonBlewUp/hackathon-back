<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\HotelRestorationEnum;
use App\Enum\HotelTypeCityEnum;
use Symfony\Component\Validator\Attribute\Group;

#[ApiResource]
#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\Table(name: 'MTC_HOTEL')]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'HTL_ID')]
    #[Group(['hotel_like'])]
    private int $id;

    #[ORM\Column(length: 255, name: 'HTL_NAME')]
    #[Group(['hotel_like'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, name: 'HTL_ADRESS')]
    #[Group(['hotel_like'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, name: 'HTL_CITY')]
    #[Group(['hotel_like'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, name: 'HTL_COUNTRY')]
    #[Group(['hotel_like'])]
    private ?string $country = null;

    #[ORM\Column(length: 255, name: 'HTL_DESCRIPTION')]
    #[Group(['hotel_like'])]
    private ?string $description = null;

    #[ORM\Column(name: 'HTL_CHILDREN')]
    #[Group(['hotel_like'])]
    private ?bool $children = null;

    #[ORM\Column(name: 'HTL_ANIMAL')]
    #[Group(['hotel_like'])]
    private ?bool $animal = null;

    #[ORM\Column(name: 'HTL_TYPE_CITY', enumType: HotelTypeCityEnum::class)]
    #[Group(['hotel_like'])]
    private HotelTypeCityEnum $typeCity;

    #[ORM\Column(length: 255, name: 'HTL_TRANSPORT')]
    #[Group(['hotel_like'])]
    private array $transport = [];

    #[ORM\Column(name: 'HTL_RESTORATION', enumType: HotelRestorationEnum::class)]
    #[Group(['hotel_like'])]
    private HotelRestorationEnum $restoration;

    #[ORM\Column(name: 'HTL_WELLNESS')]
    #[Group(['hotel_like'])]
    private array $wellness = [];

    #[ORM\Column(name: 'HTL_BUSINESS_SERVICE')]
    #[Group(['hotel_like'])]
    private array $business = [];

    #[ORM\Column(name: 'HTL_COMFORT')]
    #[Group(['hotel_like'])]
    private array $comfort = [];

    #[ORM\Column(name: 'HTL_ADD_SERVICES')]
    #[Group(['hotel_like'])]
    private array $addServices = [];

    #[ORM\Column(name: 'HTL_PMR')]
    #[Group(['hotel_like'])]
    private ?bool $pmr = null;

    #[ORM\Column(name: 'HTL_BABY')]
    #[Group(['hotel_like'])]
    private ?bool $baby = null;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[ORM\JoinColumn(name:'USR_ID',referencedColumnName:'USR_ID')]
    private User $user;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\OneToMany(targetEntity: Room::class, mappedBy: 'hotel')]
    private Collection $rooms;

    /**
     * @var Collection<int, Threshold>
     */
    #[ORM\OneToMany(targetEntity: Threshold::class, mappedBy: 'hotel')]
    private Collection $thresholds;

    #[ORM\ManyToOne(inversedBy: 'hotels')]
    #[ORM\JoinColumn(name:'CAT_ID',referencedColumnName:'CAT_ID')]
    private ?Categorie $categorie = null;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->thresholds = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

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

    public function isChildren(): ?bool
    {
        return $this->children;
    }

    public function setChildren(bool $children): static
    {
        $this->children = $children;

        return $this;
    }

    public function isAnimal(): ?bool
    {
        return $this->animal;
    }

    public function setAnimal(bool $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getTypeCity(): HotelTypeCityEnum
    {
        return $this->typeCity;
    }

    public function setTypeCity(HotelTypeCityEnum $typeCity): static
    {
        $this->typeCity = $typeCity;

        return $this;
    }

    public function getTransport(): array
    {
        return $this->transport;
    }

    public function setTransport(array $transport): static
    {
        $this->transport = $transport;

        return $this;
    }

    public function getRestoration(): HotelRestorationEnum
    {
        return $this->restoration;
    }

    public function setRestoration(HotelRestorationEnum $restoration): static
    {
        $this->restoration = $restoration;

        return $this;
    }

    public function getWellness(): array
    {
        return $this->wellness;
    }

    public function setWellness(array $wellness): static
    {
        $this->wellness = $wellness;

        return $this;
    }

    public function getBusiness(): array
    {
        return $this->business;
    }

    public function setBusiness(array $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getComfort(): array
    {
        return $this->comfort;
    }

    public function setComfort(array $comfort): static
    {
        $this->comfort = $comfort;

        return $this;
    }

    public function getAddServices(): array
    {
        return $this->addServices;
    }

    public function setAddServices(array $addServices): static
    {
        $this->addServices = $addServices;

        return $this;
    }

    public function isPmr(): ?bool
    {
        return $this->pmr;
    }

    public function setPmr(bool $pmr): static
    {
        $this->pmr = $pmr;

        return $this;
    }

    public function isBaby(): ?bool
    {
        return $this->baby;
    }

    public function setBaby(bool $baby): static
    {
        $this->baby = $baby;

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

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
            $room->setHotel($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): static
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getHotel() === $this) {
                $room->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Threshold>
     */
    public function getThresholds(): Collection
    {
        return $this->thresholds;
    }

    public function addThreshold(Threshold $threshold): static
    {
        if (!$this->thresholds->contains($threshold)) {
            $this->thresholds->add($threshold);
            $threshold->setHotel($this);
        }

        return $this;
    }

    public function removeThreshold(Threshold $threshold): static
    {
        if ($this->thresholds->removeElement($threshold)) {
            // set the owning side to null (unless already changed)
            if ($threshold->getHotel() === $this) {
                $threshold->setHotel(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
