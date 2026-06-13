<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Webhooks;

final readonly class DriverLocationUpdated extends WebhookEvent
{
    public function timestamp(): ?int
    {
        return $this->intValue('currentTimeStamp');
    }

    public function orderId(): ?int
    {
        return $this->intValue('orderId');
    }

    public function companyId(): ?int
    {
        return $this->intValue('companyId');
    }

    public function latitude(): ?float
    {
        return $this->floatValue('latitude');
    }

    public function longitude(): ?float
    {
        return $this->floatValue('longitude');
    }
}
