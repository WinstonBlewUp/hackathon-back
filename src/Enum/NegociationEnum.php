<?php

namespace App\Enum;

enum NegociationEnum: string
{
    case PENDING_HOTELIER = 'pendingHotelier';
    case PENDING_CLIENT = 'pendingClient';
    case ACCEPTED_HOTELIER = 'acceptedHotelier';
    case REFUSED_HOTELIER = 'refusedHotelier';
    case REFUSED_CLIENT = 'refusedClient';
    case REFUSED_NO_DISP = 'refusedNoDisp';
}