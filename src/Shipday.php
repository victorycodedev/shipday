<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday;

use GuzzleHttp\ClientInterface;
use Victorycodedev\Shipday\Auth\BasicAuth;
use Victorycodedev\Shipday\Http\Client;
use Victorycodedev\Shipday\Http\ClientFactory;
use Victorycodedev\Shipday\Resources\Carriers;
use Victorycodedev\Shipday\Resources\OnDemand;
use Victorycodedev\Shipday\Resources\Orders;
use Victorycodedev\Shipday\Resources\PickupOrders;
use Victorycodedev\Shipday\Resources\Tracking;

final readonly class Shipday
{
    public function __construct(private Client $client) {}

    public static function make(string $apiKey, string $baseUri = Client::DEFAULT_BASE_URI, ?ClientInterface $client = null): self
    {
        return new self(ClientFactory::make(new BasicAuth($apiKey), $baseUri, $client));
    }

    public function orders(): Orders
    {
        return new Orders($this->client);
    }

    public function pickupOrders(): PickupOrders
    {
        return new PickupOrders($this->client);
    }

    public function carriers(): Carriers
    {
        return new Carriers($this->client);
    }

    public function tracking(): Tracking
    {
        return new Tracking($this->client);
    }

    public function onDemand(): OnDemand
    {
        return new OnDemand($this->client);
    }
}