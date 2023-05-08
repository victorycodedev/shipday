<?php

namespace Victorycodedev\Shipday;

interface DeliveryProvider
{
    // insert order
    public function insertOrder(array $payload): array;

    //update order
    public function updateOrder(string $orderId, array $payload): array;

    //active orders
    public function getActiveOrders(): array;

    //order details
    public function getOrderDetails(string $orderId): array;

    //query order
    public function queryOrder(array $payload): array;

    //delete order
    public function deleteOrder(string $orderId): array|null;

    //assign order to driver
    public function assignOrderToDriver(string $orderId, string $carrierId): array;

    //update order status
    public function updateOrderStatus(string $orderId, array $payload): array;

    // ready to pickup
    public function readyToPickup(string $orderId): array;

    //add driver
    public function addDriver(array $payload): array;

    // list of drivers
    public function drivers(): array;

    //delete driver
    public function deleteDriver(string $carrierId): array|null;

    //get driver details
    public function getDriverDetails(string $carrierId): array;
}
