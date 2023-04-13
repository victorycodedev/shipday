<?php

use Victorycodedev\Shipday\OnDemandDelivery;

it('has insttiates the delivery class', function () {
    $delivery = new OnDemandDelivery('api-key');

    expect($delivery)->toBeInstanceOf(OnDemandDelivery::class);
});
