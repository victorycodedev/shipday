<?php

namespace Victorycodedev\Shipday;

use GuzzleHttp\Client;

class Delivery implements DeliveryProvider
{
    use MakeRequest;
    /**
     * GuzzleHttp Client.
     */
    protected Client $client;

    public function __construct(protected string $apiKey, Client $client = null)
    {
        $this->client = $client ?? new Client([
            'base_uri'    => 'https://api.shipday.com',
            'http_errors' => false,
            'headers'     => [
                'Authorization'    => "Basic {$this->apiKey}",
                'Content-Type'     => 'application/json',
                'Accept'           => 'application/json',
            ],
        ]);
    }

    /**
     * Insert order.
     *
     * @param array $payload
     *
     * @return array
     */
    public function insertOrder(array $payload): array
    {
        return $this->request('POST', '/orders', $payload);
    }

    /**
     * Update order.
     *
     * @param string $orderId
     * @param array  $payload
     *
     * @return array
     */
    public function updateOrder(string $orderId, array $payload): array
    {
        return $this->request('PUT', "/order/edit/{$orderId}", $payload);
    }

    /**
     * Active orders.
     *
     * @return array
     */
    public function getActiveOrders(): array
    {
        return $this->request('GET', '/orders');
    }

    /**
     * Order details.
     *
     * @param string $orderId
     *
     * @return array
     */
    public function getOrderDetails(string $orderNumber): array
    {
        return $this->request('GET', "/orders/{$orderNumber}");
    }

    /**
     * Query order.
     *
     * @param array $payload
     *
     * @return array
     */
    public function queryOrder(array $payload): array
    {
        return $this->request('POST', '/orders/query', $payload);
    }

    /**
     * Delete order.
     *
     * @param string $orderId
     */
    public function deleteOrder(string $orderId): array|null
    {
        return $this->request('DELETE', "/orders/{$orderId}");
    }

    /**
     * Assign order to driver.
     *
     * @param string $orderId
     * @param string $carrierId
     *
     * @return array
     */
    public function assignOrderToDriver(string $orderId, string $carrierId): array
    {
        return $this->request('PUT', "/orders/assign/{$orderId}/{$carrierId}");
    }

    /**
     * Update order status.
     *
     * @param string $orderId
     * @param array  $payload
     *
     * @return array
     */
    public function updateOrderStatus(string $orderId, array $payload): array
    {
        return $this->request('PUT', "/orders/{$orderId}/status", $payload);
    }

    /**
     * Ready to pickup.
     *
     * @param string $orderId
     *
     * @return array
     */
    public function readyToPickup(string $orderId): array
    {
        return $this->request('PUT', "/orders/{$orderId}/meta");
    }

    /**
     * Add driver.
     *
     * @param array $payload
     *
     * @return array
     */
    public function addDriver(array $payload): array
    {
        return $this->request('POST', '/carriers', $payload);
    }

    /**
     * List of drivers.
     *
     * @return array
     */
    public function drivers(): array
    {
        return $this->request('GET', '/carriers');
    }

    /**
     * Delete driver.
     *
     * @param string $carrierId
     */
    public function deleteDriver(string $carrierId): array|null
    {
        return $this->request('DELETE', "/carriers/{$carrierId}");
    }

    /**
     * Get driver details.
     *
     * @param string $carrierId
     *
     * @return array
     */
    public function getDriverDetails(string $carrierId): array
    {
        $drivers = $this->drivers();
        //loop through the drivers and return the driver with the carrierId
        foreach ($drivers as $driver) {
            if ($driver['id'] === intval($carrierId)) {
                return $driver;
            }
        }

        return [];
    }
}
