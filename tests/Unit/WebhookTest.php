<?php

declare(strict_types=1);

use Victorycodedev\Shipday\Exceptions\ShipdayException;
use Victorycodedev\Shipday\Webhooks\DriverLocationUpdated;
use Victorycodedev\Shipday\Webhooks\OrderStatusUpdated;
use Victorycodedev\Shipday\Webhooks\ShipdayWebhook;

it('parses and validates order status webhooks', function () {
    $event = ShipdayWebhook::parse(json_encode([
        'timestamp' => 1684644196349,
        'event' => 'ORDER_COMPLETED',
        'order_status' => 'ALREADY_DELIVERED',
        'order' => [
            'id' => 123456,
            'order_number' => '808713698',
        ],
        'carrier' => [
            'id' => 134,
            'name' => 'Jane Doe',
        ],
    ], JSON_THROW_ON_ERROR), ['token' => ['secret']], 'secret');

    expect($event)->toBeInstanceOf(OrderStatusUpdated::class)
        ->and($event->event())->toBe('ORDER_COMPLETED')
        ->and($event->status())->toBe('ALREADY_DELIVERED')
        ->and($event->orderId())->toBe(123456)
        ->and($event->orderNumber())->toBe('808713698')
        ->and($event->carrier())->toBe(['id' => 134, 'name' => 'Jane Doe']);
});

it('parses beta driver location webhooks', function () {
    $event = ShipdayWebhook::parse(json_encode([
        'event' => 'LOCATION_UPDATE',
        'orderId' => 12345,
        'companyId' => 67890,
        'latitude' => 37.7749,
        'longitude' => -122.4194,
        'currentTimeStamp' => 1631234567890,
    ], JSON_THROW_ON_ERROR), ['Token' => 'secret'], 'secret');

    expect($event)->toBeInstanceOf(DriverLocationUpdated::class)
        ->and($event->event())->toBe('LOCATION_UPDATE')
        ->and($event->orderId())->toBe(12345)
        ->and($event->companyId())->toBe(67890)
        ->and($event->latitude())->toBe(37.7749)
        ->and($event->longitude())->toBe(-122.4194)
        ->and($event->timestamp())->toBe(1631234567890);
});

it('rejects invalid webhook tokens', function () {
    ShipdayWebhook::parse('{"event":"ORDER_COMPLETED"}', ['token' => 'wrong'], 'secret');
})->throws(ShipdayException::class, 'Invalid Shipday webhook token.');

it('rejects invalid webhook json', function () {
    ShipdayWebhook::parse('{broken-json}', [], null);
})->throws(ShipdayException::class);
