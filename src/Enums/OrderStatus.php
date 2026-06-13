<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Enums;

enum OrderStatus: string
{
    case Started = 'STARTED';
    case PickedUp = 'PICKED_UP';
    case ReadyToDeliver = 'READY_TO_DELIVER';
    case AlreadyDelivered = 'ALREADY_DELIVERED';
    case Incomplete = 'INCOMPLETE';
    case FailedDelivery = 'FAILED_DELIVERY';
}
