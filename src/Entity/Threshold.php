<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ThresholdRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ThresholdRepository::class)]
#[ORM\Table(name: 'MTC_THRESHOLD')]
class Threshold
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'THR_ID')]
    private int $id;

    #[ORM\Column(name: 'THR_MINIMUM')]
    private int $minimum;

    #[ORM\Column(name: 'THR_MAXIMUM')]
    private int $maximum;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'THR_START_DATE')]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'THR_END_DATE')]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'thresholds')]
    #[ORM\JoinColumn(name:'HTL_ID',referencedColumnName:'HTL_ID')]
    private ?Hotel $hotel = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMinimum(): int
    {
        return $this->minimum;
    }

    public function setMinimum(int $minimum): static
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getMaximum(): int
    {
        return $this->maximum;
    }

    public function setMaximum(int $maximum): static
    {
        $this->maximum = $maximum;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

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
}
