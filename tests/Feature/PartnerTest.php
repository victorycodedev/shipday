<?php

declare(strict_types=1);

use Victorycodedev\Shipday\Enums\PartnerOrderStatus;

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
    'orders query' => ['POST', '/partner/orders', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->orders()->query(['from' => '2026-01-01'])],
    'completed orders' => ['GET', '/partner/members/1234/completedOrders', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->orders()->completed(1234)],
    'members details' => ['GET', '/partner/members', fn (Victorycodedev\Shipday\PartnerShipday $partner) => $partner->members()->details()],
]);

it('accepts partner order status enum when querying orders', function () {
    $history = [];
    $partner = partnerShipdayWithResponses([['body' => '[]']], $history);

    $partner->orders()->query([
        'companyId' => '1234, 1235',
        'orderStatus' => PartnerOrderStatus::Active,
        'startCursor' => 1,
        'endCursor' => 25,
    ]);

    $request = $history[0]['request'];

    expect($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri()->getPath())->toBe('/partner/orders')
        ->and(json_decode((string) $request->getBody(), true))->toBe([
            'companyId' => '1234, 1235',
            'orderStatus' => 'ACTIVE',
            'startCursor' => 1,
            'endCursor' => 25,
        ]);
});
