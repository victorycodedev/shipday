<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Enums;

enum WebhookEventType: string
{
    case OrderAssigned = 'ORDER_ASSIGNED';
    case OrderAcceptedAndStarted = 'ORDER_ACCEPTED_AND_STARTED';
    case OrderOnTheWay = 'ORDER_ONTHEWAY';
    case OrderCompleted = 'ORDER_COMPLETED';
    case OrderFailed = 'ORDER_FAILED';
    case OrderIncomplete = 'ORDER_INCOMPLETE';
    case OrderDeleted = 'ORDER_DELETE';
    case OrderInserted = 'ORDER_INSERTED';
    case OrderPickedUp = 'ORDER_PIKEDUP';
    case OrderUnassigned = 'ORDER_UNASSIGNED';
    case OrderPickedUpRemoved = 'ORDER_PIKEDUP_REMOVED';
    case OrderOnTheWayRemoved = 'ORDER_ONTHEWAY_REMOVED';
    case OrderPodUpload = 'ORDER_POD_UPLOAD';
    case LocationUpdate = 'LOCATION_UPDATE';
}
