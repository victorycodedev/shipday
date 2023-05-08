# Shipday PHP SDK

Shipday PHP sdk provides easier access to Shipday API's from PHP applications and scripts.

## Installation

Use the package manager composer to install this package.

```bash
composer require victorycodedev/shipday
```

## Usage

```php
use Victorycodedev\Shipday\Delivery;

 $delivery = new Delivery($apiKey);

 // Sign up on https://www.shipday.com to grab your API key.

```
## Delivery Orders

Insert Order

```php

//INSERT AN ORDER
 $orderDetails = [
        "orderNumber" => "99qT5A",
        "customerName" => "Mr. Jhon Mason",
        "customerAddress" => "556 Crestlake Dr, San Francisco, CA 94132, USA",
        "customerEmail" => "jhonMason@gmail.com",
        "customerPhoneNumber" => "+14152392212",
        "restaurantName" => "Popeyes Louisiana Kitchen",
        "restaurantAddress" => "890 Geneva Ave, San Francisco, CA 94112, United States",
        "restaurantPhoneNumber" => "+14152392013",
        "expectedDeliveryDate" => "2021-06-03",
        "expectedPickupTime" => "17:45:00",
        "expectedDeliveryTime" => "19:22:00",
        "pickupLatitude" => 41.53867,
        "pickupLongitude" => -72.0827,
        "deliveryLatitude" => 41.53867,
        "deliveryLongitude" => -72.0827,
        "tips" => 2.5,
        "tax" => 1.5,
        "discountAmount" => 1.5,
        "deliveryFee" => 3,
        "totalOrderCost" => 13.47,
        "deliveryInstruction" => "fast",
        "orderSource" => "Seamless",
        "additionalId" => "4532",
        "clientRestaurantId" => 12,
        "paymentMethod" => "credit_card",
        "creditCardType" => "visa",
        "creditCardId" => 1234
    ];

 $order = $delivery->insertOrder($orderDetails);
```

Edit/Update an Order

````php

$orderId = 002002;

$details = [
    'orderId' => $orderId,
    ....
];

$response = $delivery->updateOrder($orderId, $details);

````

Retrieve Active Orders

````php

$orders = $delivery->getActiveOrders();

````

Retrieve Order Details

````php

$order = $delivery->getOrderDetails('test order 1')[0];


````

Orders Query

````php

$orders = $delivery->queryOrder([
    ...
]);

````

Delete Order

````php

$delivery->deleteOrder('ENTER ORDER ID');

````

Assign Order to Driver

````php

$delivery->assignOrderToDriver('ENTER ORDER ID', 'ENTER CARRIER ID');

````

Order Status Update

````php

$response = $delivery->updateOrderStatus('ENTER ORDER ID', [
   'status' => 'STARTED',
]);

````

Order Ready to Pickup

````php

$delivery->readyToPickup('ENTER ORDER ID');

````


Add a carrier/driver

````php

$driver = $delivery->addDriver([...]);

````

Retrieve Carriers

````php

$drivers = $delivery->drivers();

````

Retrieve a single Carrier

````php

$driver = $delivery->getDriverDetails('Enter carrierId');

````

Delete a carrier

````php

$response = $delivery->deleteDriver('CARRIER ID');

````

## On-Demand Delivery

```php
use Victorycodedev\Shipday\OnDemandDelivery;

$ondemandDelivery = new OnDemandDelivery($apiKey);

 // Sign up on https://www.shipday.com to grab your API key.

```

Services : Get a list of 3rd party delivery service providers available

````php

$response = $ondemandDelivery->services();

````

Estimate: Get estimate from 3rd party service providers (Service availability, Price, Wait time etc.)

````php

$response = $ondemandDelivery->estimate('ORDER ID');

````

Assign: Assign to a specific 3rd party delivery service provider. Usually, after getting an estimate.

````php

$response = $ondemandDelivery->assign([
    //... KEY VALUE PAIR PAYLOAD
]);

````

Details: Get order and status details for an assigned order to 3rd party service provider.

````php

$response = $ondemandDelivery->getDetails('ORDER ID');

````


Cancel an assigned order 

````php

$response = $ondemandDelivery->cancel('ORDER ID');

````

Availaiblity: Get availability information

````php

$response = $ondemandDelivery->availability([
     //... KEY VALUE PAIR PAYLOAD
]);


````

## API Reference
All API references can be found on shipday documentation website. https://docs.shipday.com/reference/shipday-api

## Security
If you discover any security related issues, please open an issue.

## How can I thank you?
Why not star the github repo? I'd love the attention! you can share the link for this repository on Twitter? 

Don't forget to [follow me on twitter!](https://twitter.com/EfekpoguaVicto4)

Thanks! Efekpogua Victory.

## License

[MIT](./LICENSE.md)
