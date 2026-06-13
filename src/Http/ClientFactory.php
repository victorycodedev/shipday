<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Victorycodedev\Shipday\Auth\Authenticator;

final class ClientFactory
{
    public static function make(Authenticator $authenticator, string $baseUri = Client::DEFAULT_BASE_URI, ?ClientInterface $client = null): Client
    {
        return new Client(
            client: $client ?? new GuzzleClient(['base_uri' => rtrim($baseUri, '/')]),
            authenticator: $authenticator,
        );
    }
}
