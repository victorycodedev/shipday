<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Partner;

use Victorycodedev\Shipday\Http\Client;

final readonly class PartnerOrders
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<mixed>
     */
    public function completed(array $query = []): array
    {
        return $this->client->get('/partner/orders/completed', $query);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function query(array $payload): array
    {
        return $this->client->post('/partner/orders', $payload);
    }
}
