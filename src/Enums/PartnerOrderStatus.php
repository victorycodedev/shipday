<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Enums;

enum PartnerOrderStatus: string
{
    case Active = 'ACTIVE';
    case NotAssigned = 'NOT_ASSIGNED';
    case NotAccepted = 'NOT_ACCEPTED';
    case NotStartedYet = 'NOT_STARTED_YET';
    case Started = 'STARTED';
    case PickedUp = 'PICKED_UP';
    case ReadyToDeliver = 'READY_TO_DELIVER';
    case AlreadyDelivered = 'ALREADY_DELIVERED';
    case FailedDelivery = 'FAILED_DELIVERY';
    case Incomplete = 'INCOMPLETE';
}
