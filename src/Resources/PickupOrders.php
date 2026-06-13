<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Resources;

use Victorycodedev\Shipday\Http\Client;

final readonly class PickupOrders
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function create(array $payload): array
    {
        return $this->client->post('/pickup-orders', $payload);
    }

    /**
     * @return array<mixed>
     */
    public function find(string|int $orderId): array
    {
        return $this->client->get(sprintf('/pickup-orders/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function update(string|int $orderId, array $payload): array
    {
        return $this->client->put(sprintf('/pickup-orders/%s', rawurlencode((string) $orderId)), $payload);
    }

    /**
     * @return array<mixed>
     */
    public function delete(string|int $orderId): array
    {
        return $this->client->delete(sprintf('/pickup-orders/%s', rawurlencode((string) $orderId)));
    }
}
