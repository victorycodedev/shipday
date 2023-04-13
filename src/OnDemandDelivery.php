<?php

namespace Victorycodedev\Shipday;

use GuzzleHttp\Client;

class OnDemandDelivery implements OnDemandDeliveryProvider
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
                'Authorization' => "Basic {$this->apiKey}",
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);
    }

    /**
     * Get a list of 3rd party delivery service providers available.
     *
     * @return array
     */
    public function services(): array
    {
        return $this->request('GET', '/on-demand/services');
    }

    /**
     * Estimate.
     *
     * @param string $orderId
     *
     * @return array
     */
    public function estimate(string $orderId): array
    {
        return $this->request('GET', "/on-demand/estimate/{$orderId}");
    }

    /**
     * Assign to a specific 3rd party delivery service provider.
     *
     * @param array $payload
     *
     * @return array
     */
    public function assign(array $payload): array
    {
        return $this->request('POST', '/on-demand/assign', $payload);
    }

    /**
     * Get details.
     *
     * @param string $orderId
     *
     * @return array
     */
    public function getDetails(string $orderId): array
    {
        return $this->request('GET', "/on-demand/details/{$orderId}");
    }

    /**
     * Cancel an assigned order from 3rd party service provider.
     *
     * @param string $orderId
     *
     * @return array
     */
    public function cancel(string $orderId): array
    {
        return $this->request('POST', "/on-demand/cancel/{$orderId}");
    }

    /**
     * Get availability information of both in-house and 3rd party providers for specific pickup and delivery without creating an order.
     *
     * @param array $payload
     *
     * @return array
     */
    public function availability(array $payload): array
    {
        return $this->request('POST', '/driver/availability', $payload);
    }
}
