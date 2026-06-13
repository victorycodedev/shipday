<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Resources;

use Victorycodedev\Shipday\Http\Client;

final readonly class Tracking
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @return array<mixed>
     */
    public function progress(string $trackingId, bool $includeStaticData = false): array
    {
        return $this->client->get(
            sprintf('/order/progress/%s', rawurlencode($trackingId)),
            ['isStaticDataRequired' => $includeStaticData ? 'true' : 'false'],
        );
    }
}
