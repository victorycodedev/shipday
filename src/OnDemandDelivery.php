<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday;

use GuzzleHttp\ClientInterface;

/**
 * @deprecated Use Shipday::make($apiKey)->onDemand() instead.
 */
final readonly class OnDemandDelivery
{
    private Shipday $shipday;

    public function __construct(string $apiKey, ?ClientInterface $client = null)
    {
        $this->shipday = Shipday::make($apiKey, client: $client);
    }

    /**
     * @return array<mixed>
     */
    public function services(): array
    {
        return $this->shipday->onDemand()->services();
    }

    /**
     * @return array<mixed>
     */
    public function estimate(string $orderId): array
    {
        return $this->shipday->onDemand()->estimate($orderId);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function assign(array $payload): array
    {
        return $this->shipday->onDemand()->assign($payload);
    }

    /**
     * @return array<mixed>
     */
    public function getDetails(string $orderId): array
    {
        return $this->shipday->onDemand()->details($orderId);
    }

    /**
     * @return array<mixed>
     */
    public function cancel(string $orderId): array
    {
        return $this->shipday->onDemand()->cancel($orderId);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function availability(array $payload): array
    {
        return $this->shipday->onDemand()->availability($payload);
    }
}
