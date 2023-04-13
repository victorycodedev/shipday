<?php

use Victorycodedev\Shipday\Delivery;

it('has insttiates the delivery class', function () {
    $delivery = new Delivery('api-key');

    expect($delivery)->toBeInstanceOf(Delivery::class);
});
