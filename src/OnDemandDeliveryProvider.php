<?php

namespace Victorycodedev\Shipday;

interface OnDemandDeliveryProvider
{
    //Get a list of 3rd party delivery service providers available
    public function services(): array;

    //estimate 
    public function estimate(string $orderId): array;

    //Assign to a specific 3rd party delivery service provider. Usually, after getting an estimate.
    public function assign(array $payload): array;

    //get details 
    public function getDetails(string $orderId): array;

    //Cancel an assigned order from 3rd party service provider
    public function cancel(string $orderId): array;

    //Get availability information of both in-house and 3rd party providers for specific pickup and delivery without creating an order.
    public function availability(array $payload): array;
}
