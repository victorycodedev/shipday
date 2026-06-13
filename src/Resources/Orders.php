<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Resources;

use Victorycodedev\Shipday\Http\Client;

final readonly class Orders
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
        return $this->client->post('/orders', $payload);
    }

    /**
     * @return array<mixed>
     */
    public function active(): array
    {
        return $this->client->get('/orders');
    }

    /**
     * @return array<mixed>
     */
    public function find(string $orderNumber): array
    {
        return $this->client->get(sprintf('/orders/%s', rawurlencode($orderNumber)));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function update(string|int $orderId, array $payload): array
    {
        return $this->client->put(sprintf('/order/edit/%s', rawurlencode((string) $orderId)), $payload);
    }

    /**
     * @return array<mixed>
     */
    public function delete(string|int $orderId): array
    {
        return $this->client->delete(sprintf('/orders/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function query(array $payload): array
    {
        return $this->client->post('/orders/query', $payload);
    }

    /**
     * @return array<mixed>
     */
    public function assignDriver(string|int $orderId, string|int $carrierId): array
    {
        return $this->client->put(sprintf(
            '/orders/assign/%s/%s',
            rawurlencode((string) $orderId),
            rawurlencode((string) $carrierId),
        ));
    }

    /**
     * @return array<mixed>
     */
    public function unassignDriver(string|int $orderId): array
    {
        return $this->client->put(sprintf('/orders/unassign/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @return array<mixed>
     */
    public function readyToPickup(string|int $orderId): array
    {
        return $this->client->put(sprintf('/orders/%s/meta', rawurlencode((string) $orderId)));
    }

    /**
     * @param array<string, mixed>|string $status
     *
     * @return array<mixed>
     */
    public function updateStatus(string|int $orderId, array|string $status): array
    {
        $payload = is_array($status) ? $status : ['status' => $status];

        return $this->client->put(sprintf('/orders/%s/status', rawurlencode((string) $orderId)), $payload);
    }
}
