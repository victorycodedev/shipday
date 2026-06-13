<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Partner;

use Victorycodedev\Shipday\Enums\PartnerOrderStatus;
use Victorycodedev\Shipday\Http\Client;

final readonly class PartnerOrders
{
    public function __construct(private Client $client) {}

    /**
     * Returns up to 100 completed orders for a member from the past 24 hours.
     *
     * @return array<mixed>
     */
    public function completed(string|int $companyId): array
    {
        return $this->client->get(sprintf(
            '/partner/members/%s/completedOrders',
            rawurlencode((string) $companyId),
        ));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function query(array $payload): array
    {
        if (($payload['orderStatus'] ?? null) instanceof PartnerOrderStatus) {
            $payload['orderStatus'] = $payload['orderStatus']->value;
        }

        return $this->client->post('/partner/orders', $payload);
    }
}