<?php

namespace App\Enum;

enum HotelRestorationEnum: string
{
    case RESTAURANT = 'Restauration Gastronomique';
    case PENSION = 'Pension complète';
    case PETIT = 'Petit déjeuner';
}