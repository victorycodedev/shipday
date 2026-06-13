<?php

declare(strict_types=1);

use Victorycodedev\Shipday\Exceptions\ShipdayException;
use Victorycodedev\Shipday\Enums\OrderStatus;

it('creates a delivery order', function () {
    $history = [];
    $shipday = shipdayWithResponses([
        ['body' => '{"success":true,"orderId":123}'],
    ], $history);

    $response = $shipday->orders()->create(['orderNumber' => 'A-1']);
    $request = $history[0]['request'];

    expect($response)->toBe(['success' => true, 'orderId' => 123])
        ->and($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri()->getPath())->toBe('/orders')
        ->and($request->getHeaderLine('Authorization'))->toBe('Basic test-api-key')
        ->and($request->getHeaderLine('x-api-key'))->toBe('')
        ->and(json_decode((string) $request->getBody(), true))->toBe(['orderNumber' => 'A-1']);
});

it('can include an optional x api key header', function () {
    $history = [];
    $mock = new GuzzleHttp\Handler\MockHandler([
        new GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"success":true}'),
    ]);
    $stack = GuzzleHttp\HandlerStack::create($mock);
    $stack->push(GuzzleHttp\Middleware::history($history));

    $shipday = Victorycodedev\Shipday\Shipday::make(
        apiKey: 'test-api-key',
        client: new GuzzleHttp\Client(['handler' => $stack, 'base_uri' => 'https://api.shipday.com']),
        xApiKey: 'test-x-api-key',
    );

    $shipday->orders()->active();

    expect($history[0]['request']->getHeaderLine('Authorization'))->toBe('Basic test-api-key')
        ->and($history[0]['request']->getHeaderLine('x-api-key'))->toBe('test-x-api-key');
});

it('covers delivery order endpoints', function (string $method, string $path, Closure $call) {
    $history = [];
    $shipday = shipdayWithResponses([
        ['body' => '{"ok":true}'],
    ], $history);

    $call($shipday);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe($method)
        ->and((string) $request->getUri()->getPath())->toBe($path);
})->with([
    'active' => ['GET', '/orders', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->active()],
    'find' => ['GET', '/orders/ORDER-1', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->find('ORDER-1')],
    'update' => ['PUT', '/order/edit/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->update(123, ['customerName' => 'Ada'])],
    'delete' => ['DELETE', '/orders/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->delete(123)],
    'query' => ['POST', '/orders/query', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->query(['orderNumber' => 'ORDER-1'])],
    'assign driver' => ['PUT', '/orders/assign/123/456', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->assignDriver(123, 456)],
    'unassign driver' => ['PUT', '/orders/unassign/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->unassignDriver(123)],
    'ready to pickup' => ['PUT', '/orders/123/meta', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->readyToPickup(123)],
    'status update' => ['PUT', '/orders/123/status', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->orders()->updateStatus(123, 'STARTED')],
]);

it('accepts the order status enum when updating status', function () {
    $history = [];
    $shipday = shipdayWithResponses([
        ['body' => '{"success":true,"orderId":123,"response":"UPDATED STATUS: PICKED_UP"}'],
    ], $history);

    $shipday->orders()->updateStatus(123, OrderStatus::PickedUp);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe('PUT')
        ->and((string) $request->getUri()->getPath())->toBe('/orders/123/status')
        ->and(json_decode((string) $request->getBody(), true))->toBe(['status' => 'PICKED_UP']);
});

it('sends ready to pickup payload required by shipday', function () {
    $history = [];
    $shipday = shipdayWithResponses([
        ['status' => 202, 'body' => ''],
    ], $history);

    $response = $shipday->orders()->readyToPickup(123);
    $request = $history[0]['request'];

    expect($response)->toBe([])
        ->and($request->getMethod())->toBe('PUT')
        ->and((string) $request->getUri()->getPath())->toBe('/orders/123/meta')
        ->and(json_decode((string) $request->getBody(), true))->toBe(['readyToPickup' => true]);
});

it('throws one rich exception for api errors', function () {
    $history = [];
    $shipday = shipdayWithResponses([
        [
            'status' => 429,
            'headers' => ['Retry-After' => ['30']],
            'body' => '{"id":99,"error":"Too Many Requests","details":{"limit":3}}',
        ],
    ], $history);

    try {
        $shipday->orders()->active();
    } catch (ShipdayException $exception) {
        expect($exception->statusCode())->toBe(429)
            ->and($exception->errorId())->toBe(99)
            ->and($exception->errorName())->toBe('Too Many Requests')
            ->and($exception->details())->toBe(['limit' => 3])
            ->and($exception->retryAfter())->toBe('30');

        return;
    }

    $this->fail('ShipdayException was not thrown.');
});
