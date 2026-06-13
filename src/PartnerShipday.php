<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday;

use GuzzleHttp\ClientInterface;
use Victorycodedev\Shipday\Auth\PartnerAuth;
use Victorycodedev\Shipday\Http\Client;
use Victorycodedev\Shipday\Http\ClientFactory;
use Victorycodedev\Shipday\Partner\Members;
use Victorycodedev\Shipday\Partner\PartnerOrders;

final readonly class PartnerShipday
{
    public function __construct(private Client $client) {}

    public static function make(string $partnerApiKey, string $baseUri = Client::DEFAULT_BASE_URI, ?ClientInterface $client = null): self
    {
        return new self(ClientFactory::make(new PartnerAuth($partnerApiKey), $baseUri, $client));
    }

    public function orders(): PartnerOrders
    {
        return new PartnerOrders($this->client);
    }

    public function members(): Members
    {
        return new Members($this->client);
    }
}