<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Resources;

use Victorycodedev\Shipday\Http\Client;

final readonly class OnDemand
{
    public function __construct(private Client $client) {}

    /**
     * @return array<mixed>
     */
    public function services(): array
    {
        return $this->client->get('/on-demand/services');
    }

    /**
     * @return array<mixed>
     */
    public function estimate(string|int $orderId): array
    {
        return $this->client->get(sprintf('/on-demand/estimate/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function assign(array $payload): array
    {
        return $this->client->post('/on-demand/assign', $payload);
    }

    /**
     * @return array<mixed>
     */
    public function details(string|int $orderId): array
    {
        return $this->client->get(sprintf('/on-demand/details/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @return array<mixed>
     */
    public function cancel(string|int $orderId): array
    {
        return $this->client->post(sprintf('/on-demand/cancel/%s', rawurlencode((string) $orderId)));
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function availability(array $payload): array
    {
        return $this->client->post('/on-demand/availability', $payload);
    }
}