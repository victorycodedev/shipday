<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday;

use GuzzleHttp\ClientInterface;

/**
 * @deprecated Use Shipday::make($apiKey)->orders() and ->carriers() instead.
 */
final readonly class Delivery
{
    private Shipday $shipday;

    public function __construct(string $apiKey, ?ClientInterface $client = null)
    {
        $this->shipday = Shipday::make($apiKey, client: $client);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function insertOrder(array $payload): array
    {
        return $this->shipday->orders()->create($payload);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function updateOrder(string $orderId, array $payload): array
    {
        return $this->shipday->orders()->update($orderId, $payload);
    }

    /**
     * @return array<mixed>
     */
    public function getActiveOrders(): array
    {
        return $this->shipday->orders()->active();
    }

    /**
     * @return array<mixed>
     */
    public function getOrderDetails(string $orderNumber): array
    {
        return $this->shipday->orders()->find($orderNumber);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function queryOrder(array $payload): array
    {
        return $this->shipday->orders()->query($payload);
    }

    /**
     * @return array<mixed>
     */
    public function deleteOrder(string $orderId): array
    {
        return $this->shipday->orders()->delete($orderId);
    }

    /**
     * @return array<mixed>
     */
    public function assignOrderToDriver(string $orderId, string $carrierId): array
    {
        return $this->shipday->orders()->assignDriver($orderId, $carrierId);
    }

    /**
     * @return array<mixed>
     */
    public function unassignOrderFromDriver(string $orderId): array
    {
        return $this->shipday->orders()->unassignDriver($orderId);
    }

    /**
     * @param array<string, mixed>|string $payload
     *
     * @return array<mixed>
     */
    public function updateOrderStatus(string $orderId, array|string $payload): array
    {
        return $this->shipday->orders()->updateStatus($orderId, $payload);
    }

    /**
     * @return array<mixed>
     */
    public function readyToPickup(string $orderId): array
    {
        return $this->shipday->orders()->readyToPickup($orderId);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function addDriver(array $payload): array
    {
        return $this->shipday->carriers()->create($payload);
    }

    /**
     * @return array<mixed>
     */
    public function drivers(): array
    {
        return $this->shipday->carriers()->all();
    }

    /**
     * @return array<mixed>
     */
    public function deleteDriver(string $carrierId): array
    {
        return $this->shipday->carriers()->delete($carrierId);
    }
}
