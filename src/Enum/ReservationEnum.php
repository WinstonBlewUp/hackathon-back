<?php

namespace App\Enum;

enum ReservationEnum: string
{
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
}