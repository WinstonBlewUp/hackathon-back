<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentEnum;

#[ApiResource]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Table(name: 'MTC_PAYMENT')]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'PAY_ID')]
    private int $id;

    #[ORM\Column(name: 'PAY_STRIPE_ID')]
    private int $stripeId;

    #[ORM\Column(name: 'PAY_AMOUNT')]
    private int $amount;

    #[ORM\Column(name: 'PAY_STATUS', enumType: PaymentEnum::class)]
    private PaymentEnum $status;

    #[ORM\Column(length: 255, name: 'PAY_METHOD')]
    private ?string $method = null;

    #[ORM\Column(length: 255, name: 'PAY_RECEIPT')]
    private ?string $receiptUrl = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'PAY_CREATED_AT')]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(name:'USR_ID',referencedColumnName:'USR_ID')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStripeId(): int
    {
        return $this->stripeId;
    }

    public function setStripeId(int $stripeId): static
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): PaymentEnum
    {
        return $this->status;
    }

    public function setStatus(PaymentEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getReceiptUrl(): ?string
    {
        return $this->receiptUrl;
    }

    public function setReceiptUrl(string $receiptUrl): static
    {
        $this->receiptUrl = $receiptUrl;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
