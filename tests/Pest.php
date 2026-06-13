<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Victorycodedev\Shipday\PartnerShipday;
use Victorycodedev\Shipday\Shipday;

function shipdayWithResponses(array $responses, array &$history = []): Shipday
{
    $mock = new MockHandler(array_map(
        fn (array $response): Response => new Response(
            $response['status'] ?? 200,
            $response['headers'] ?? ['Content-Type' => 'application/json'],
            $response['body'] ?? '{}',
        ),
        $responses,
    ));

    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    return Shipday::make(
        apiKey: 'test-api-key',
        client: new GuzzleClient(['handler' => $stack, 'base_uri' => 'https://api.shipday.com']),
    );
}

function partnerShipdayWithResponses(array $responses, array &$history = []): PartnerShipday
{
    $mock = new MockHandler(array_map(
        fn (array $response): Response => new Response(
            $response['status'] ?? 200,
            $response['headers'] ?? ['Content-Type' => 'application/json'],
            $response['body'] ?? '{}',
        ),
        $responses,
    ));

    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));

    return PartnerShipday::make(
        partnerApiKey: 'partner-api-key',
        client: new GuzzleClient(['handler' => $stack, 'base_uri' => 'https://api.shipday.com']),
    );
}
