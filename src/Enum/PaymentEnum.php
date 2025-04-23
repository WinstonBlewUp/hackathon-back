<?php

namespace App\Enum;

enum PaymentEnum: string
{
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case PENDING = 'pending';
}