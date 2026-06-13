<?php

declare(strict_types=1);

it('covers pickup order endpoints', function (string $method, string $path, Closure $call) {
    $history = [];
    $shipday = shipdayWithResponses([['body' => '{"ok":true}']], $history);

    $call($shipday);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe($method)
        ->and((string) $request->getUri()->getPath())->toBe($path);
})->with([
    'create' => ['POST', '/pickup-orders', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->pickupOrders()->create(['orderNumber' => 'P-1'])],
    'find' => ['GET', '/pickup-orders/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->pickupOrders()->find(123)],
    'update' => ['PUT', '/pickup-orders/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->pickupOrders()->update(123, ['orderNumber' => 'P-2'])],
    'delete' => ['DELETE', '/pickup-orders/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->pickupOrders()->delete(123)],
]);

it('covers carrier endpoints', function (string $method, string $path, Closure $call) {
    $history = [];
    $shipday = shipdayWithResponses([['body' => '{"ok":true}']], $history);

    $call($shipday);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe($method)
        ->and((string) $request->getUri()->getPath())->toBe($path);
})->with([
    'all' => ['GET', '/carriers', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->carriers()->all()],
    'create' => ['POST', '/carriers', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->carriers()->create(['name' => 'Ada'])],
    'delete' => ['DELETE', '/carriers/456', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->carriers()->delete(456)],
]);

it('requests delivery progress with the static data query flag', function () {
    $history = [];
    $shipday = shipdayWithResponses([['body' => '{"ok":true}']], $history);

    $shipday->tracking()->progress('track-123', includeStaticData: true);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe('GET')
        ->and((string) $request->getUri()->getPath())->toBe('/order/progress/track-123')
        ->and((string) $request->getUri()->getQuery())->toBe('isStaticDataRequired=true');
});

it('covers on demand endpoints', function (string $method, string $path, Closure $call) {
    $history = [];
    $shipday = shipdayWithResponses([['body' => '{"ok":true}']], $history);

    $call($shipday);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe($method)
        ->and((string) $request->getUri()->getPath())->toBe($path);
})->with([
    'services' => ['GET', '/on-demand/services', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->services()],
    'estimate' => ['GET', '/on-demand/estimate/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->estimate(123)],
    'assign' => ['POST', '/on-demand/assign', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->assign(['name' => 'DoorDash', 'orderId' => 123])],
    'details' => ['GET', '/on-demand/details/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->details(123)],
    'cancel' => ['POST', '/on-demand/cancel/123', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->cancel(123)],
    'availability' => ['POST', '/on-demand/availability', fn (Victorycodedev\Shipday\Shipday $shipday) => $shipday->onDemand()->availability(['pickupAddress' => 'A', 'deliveryAddress' => 'B'])],
]);
