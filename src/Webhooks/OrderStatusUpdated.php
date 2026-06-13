<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Webhooks;

final readonly class OrderStatusUpdated extends WebhookEvent
{
    public function timestamp(): ?int
    {
        return $this->intValue('timestamp');
    }

    public function status(): ?string
    {
        return isset($this->payload['order_status']) ? (string) $this->payload['order_status'] : null;
    }

    public function orderId(): ?int
    {
        if (isset($this->payload['order']['id'])) {
            return (int) $this->payload['order']['id'];
        }

        return $this->intValue('orderId');
    }

    public function orderNumber(): ?string
    {
        return isset($this->payload['order']['order_number'])
            ? (string) $this->payload['order']['order_number']
            : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function order(): array
    {
        return is_array($this->payload['order'] ?? null) ? $this->payload['order'] : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function company(): array
    {
        return is_array($this->payload['company'] ?? null) ? $this->payload['company'] : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function carrier(): array
    {
        return is_array($this->payload['carrier'] ?? null) ? $this->payload['carrier'] : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function deliveryDetails(): array
    {
        return is_array($this->payload['delivery_details'] ?? null) ? $this->payload['delivery_details'] : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function pickupDetails(): array
    {
        return is_array($this->payload['pickup_details'] ?? null) ? $this->payload['pickup_details'] : [];
    }
}
