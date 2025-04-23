<?php

namespace App\Entity;

use App\Repository\SearchHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\HotelRestorationEnum;
use App\Enum\HotelTypeCityEnum;

#[ORM\Entity(repositoryClass: SearchHistoryRepository::class)]
#[ORM\Table(name: 'MTC_SEARCH_HISTORY')]
class SearchHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'SEA_ID')]
    private int $id;

    #[ORM\Column(name: 'SEA_ANIMAL')]
    private ?bool $animal = null;

    #[ORM\Column(name: 'SEA_CHILDREN')]
    private ?bool $children = null;

    #[ORM\Column(name: 'SEA_TYPE_CITY', enumType: HotelTypeCityEnum::class)]
    private ?HotelTypeCityEnum $typeCity = null;

    #[ORM\Column(name: 'SEA_RESTORATION', enumType: HotelRestorationEnum::class)]
    private ?HotelTypeCityEnum $restoration = null;

    #[ORM\Column(name: 'SEA_TRANSPORT')]
    private array $transport = [];

    #[ORM\Column(name: 'SEA_WELLNESS')]
    private array $wellness = [];

    #[ORM\Column(name: 'SEA_BUSINESS')]
    private array $business = [];

    #[ORM\Column(name: 'SEA_COMFORT')]
    private array $comfort = [];

    #[ORM\Column(name: 'SEA_ADD_SERVICES')]
    private array $addServices = [];

    #[ORM\Column(name: 'SEA_PMR')]
    private ?bool $pmr = null;

    #[ORM\Column(name: 'SEA_BABY')]
    private ?bool $baby = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, name: 'SEA_START_DATE')]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, name: 'SEA_END_DATE')]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(name: 'SEA_MAX_GUESTS')]
    private ?int $maxGuests = null;

    #[ORM\ManyToOne(inversedBy: 'searchHistories')]
    #[ORM\JoinColumn(name:'USR_ID',referencedColumnName:'USR_ID')]
    private ?User $user = null;

    public function getId(): int
    {
        return $this->id;
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

    public function isChildren(): ?bool
    {
        return $this->children;
    }

    public function setChildren(bool $children): static
    {
        $this->children = $children;

        return $this;
    }

    public function getTypeCity(): ?HotelTypeCityEnum
    {
        return $this->typeCity;
    }

    public function setTypeCity(HotelTypeCityEnum $typeCity): static
    {
        $this->typeCity = $typeCity;

        return $this;
    }

    public function getRestoration(): ?HotelRestorationEnum
    {
        return $this->restoration;
    }

    public function setRestoration(HotelRestorationEnum $restoration): static
    {
        $this->restoration = $restoration;

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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

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
