# Shipday PHP SDK

A modern PHP SDK for the [Shipday API](https://docs.shipday.com/reference).

Version 2 requires PHP 8.3+ and uses a resource-based API:

```php
use Victorycodedev\Shipday\Shipday;
use Victorycodedev\Shipday\Enums\OrderStatus;

$shipday = Shipday::make('your-shipday-api-key');

$order = $shipday->orders()->create([
    'orderNumber' => 'A-1001',
    'customerName' => 'Ada Lovelace',
    'customerAddress' => '556 Crestlake Dr, San Francisco, CA 94132, USA',
    'customerPhoneNumber' => '+14152392212',
    'restaurantName' => 'Popeyes Louisiana Kitchen',
    'restaurantAddress' => '890 Geneva Ave, San Francisco, CA 94112, United States',
]);
```

## Installation

```bash
composer require victorycodedev/shipday
```

## Requirements

- PHP 8.3+
- Shipday API key

Shipday authenticates regular API requests with:

```http
Authorization: Basic <API_KEY>
```

Some generated Shipday examples also show an `x-api-key` header. The SDK does not send a literal `x-api-key: null` header, but you can include an `x-api-key` value when needed:

```php
$shipday = Shipday::make(
    apiKey: 'your-shipday-api-key',
    xApiKey: 'your-x-api-key',
);
```

Partner API requests use:

```http
PARTNER-API-KEY: <PARTNER_API_KEY>
```

## Delivery Orders

```php
$shipday = Shipday::make('your-shipday-api-key');

$shipday->orders()->active();
$shipday->orders()->find('ORDER_NUMBER');
$shipday->orders()->create([...]);
$shipday->orders()->update($orderId, [...]);
$shipday->orders()->delete($orderId);
$shipday->orders()->query([...]);
$shipday->orders()->assignDriver($orderId, $carrierId);
$shipday->orders()->unassignDriver($orderId);
$shipday->orders()->readyToPickup($orderId); // sends ["readyToPickup" => true]
$shipday->orders()->updateStatus($orderId, OrderStatus::Started);
// Raw strings are also accepted for forward compatibility:
$shipday->orders()->updateStatus($orderId, 'STARTED');
```

## Pickup Orders

```php
$shipday->pickupOrders()->create([...]);
$shipday->pickupOrders()->find($orderId);
$shipday->pickupOrders()->update($orderId, [...]);
$shipday->pickupOrders()->delete($orderId);
```

## Carriers

```php
$shipday->carriers()->all();

$shipday->carriers()->create([
    'name' => 'Jane Driver',
    'email' => 'jane@example.com',
    'phoneNumber' => '+11234567890',
]);

$shipday->carriers()->delete($carrierId);
```

## Delivery Tracking

```php
$shipday->tracking()->progress(
    trackingId: 'tracking-id',
    includeStaticData: true,
);
```

## On-Demand Delivery

```php
$shipday->onDemand()->services();
$shipday->onDemand()->estimate($orderId);

$shipday->onDemand()->assign([
    'name' => 'DoorDash',
    'orderId' => $orderId,
    'tip' => 6.50,
    'estimateReference' => 'estimate-reference',
    'contactlessDelivery' => false,
    'podType' => 'PHOTO',
]);

$shipday->onDemand()->details($orderId);
$shipday->onDemand()->cancel($orderId);

$shipday->onDemand()->availability([
    'pickupAddress' => '1 Wall St, New York, NY 10005, USA',
    'deliveryAddress' => '1000 5th Ave, New York, NY 10028, USA',
]);
```

## Partner API

Partner endpoints use a separate client because Shipday requires `PARTNER-API-KEY`.

```php
use Victorycodedev\Shipday\PartnerShipday;
use Victorycodedev\Shipday\Enums\PartnerOrderStatus;

$partner = PartnerShipday::make('your-partner-api-key');

$partner->orders()->query([
    'companyId' => '1234',
    'orderStatus' => PartnerOrderStatus::Active,
    'startCursor' => 1,
    'endCursor' => 25,
]);
$partner->orders()->completed($companyId);
$partner->members()->details();
```

## Exceptions

Version 2 uses one exception class:

```php
use Victorycodedev\Shipday\Exceptions\ShipdayException;

try {
    $shipday->orders()->active();
} catch (ShipdayException $exception) {
    $exception->statusCode();
    $exception->response();
    $exception->headers();
    $exception->errorId();
    $exception->errorName();
    $exception->details();
    $exception->retryAfter();
}
```

## Webhooks

Your application still receives the HTTP webhook request. The SDK helps validate the optional Shipday webhook token, decode the payload, detect the event type, and expose useful values.

Shipday sends the validation token in a header named `token`.

### Laravel Example

```php
use Illuminate\Http\Request;
use Victorycodedev\Shipday\Enums\WebhookEventType;
use Victorycodedev\Shipday\Enums\WebhookOrderStatus;
use Victorycodedev\Shipday\Webhooks\DriverLocationUpdated;
use Victorycodedev\Shipday\Webhooks\OrderStatusUpdated;
use Victorycodedev\Shipday\Webhooks\ShipdayWebhook;

Route::post('/webhooks/shipday', function (Request $request) {
    $event = ShipdayWebhook::fromRequest(
        payload: $request->getContent(),
        headers: $request->headers->all(),
        token: config('services.shipday.webhook_token'),
    );

    if ($event instanceof OrderStatusUpdated) {
        $event->event();
        $event->eventType(); // WebhookEventType::OrderCompleted
        $event->status();
        $event->statusType(); // WebhookOrderStatus::AlreadyDelivered
        $event->orderId();
        $event->orderNumber();
        $event->order();
        $event->carrier();
    }

    if ($event instanceof DriverLocationUpdated) {
        $event->orderId();
        $event->companyId();
        $event->latitude();
        $event->longitude();
        $event->timestamp();
    }

    return response()->json(['received' => true]);
});
```

### Plain PHP Example

```php
use Victorycodedev\Shipday\Webhooks\ShipdayWebhook;

$event = ShipdayWebhook::fromGlobals(
    token: $_ENV['SHIPDAY_WEBHOOK_TOKEN'] ?? null,
);

http_response_code(200);
```

The beta driver location webhook is supported through `DriverLocationUpdated`.

## Testing

This package uses [Pest](https://pestphp.com/).

```bash
composer test
```

## Upgrade Notes From v1

The old `Delivery` and `OnDemandDelivery` classes are deprecated compatibility wrappers. New applications should use:

```php
$shipday = Shipday::make('your-shipday-api-key');
```

Method names changed to a resource style. For example:

```php
// v1
$delivery->insertOrder($payload);

// v2
$shipday->orders()->create($payload);
```

## License

[MIT](LICENSE.md)
