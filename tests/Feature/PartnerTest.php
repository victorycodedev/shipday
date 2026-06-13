<?php

declare(strict_types=1);

it('uses partner auth for partner endpoints', function (string $method, string $path, Closure $call) {
    $history = [];
    $partner = partnerShipdayWithResponses([['body' => '{"ok":true}']], $history);

    $call($partner);
    $request = $history[0]['request'];

    expect($request->getMethod())->toBe($method)
        ->and((string) $request->getUri()->getPath())->toBe($path)
        ->and($request->getHeaderLine('PARTNER-API-KEY'))->toBe('partner-api-key')
        ->and($request->getHeaderLine('Authorization'))->toBe('');
})->with([
    'completed orders' => ['GET', '/partner/orders/completed', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->orders()->completed()],
    'orders query' => ['POST', '/partner/orders', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->orders()->query(['from' => '2026-01-01'])],
    'members details' => ['GET', '/partner/members', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->members()->details()],
]);
