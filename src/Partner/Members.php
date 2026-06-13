<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Partner;

use Victorycodedev\Shipday\Http\Client;

final readonly class Members
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<mixed>
     */
    public function details(array $query = []): array
    {
        return $this->client->get('/partner/members', $query);
    }
}
