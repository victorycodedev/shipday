<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Resources;

use Victorycodedev\Shipday\Http\Client;

final readonly class Carriers
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @return array<mixed>
     */
    public function all(): array
    {
        return $this->client->get('/carriers');
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function create(array $payload): array
    {
        return $this->client->post('/carriers', $payload);
    }

    /**
     * @return array<mixed>
     */
    public function delete(string|int $carrierId): array
    {
        return $this->client->delete(sprintf('/carriers/%s', rawurlencode((string) $carrierId)));
    }
}
