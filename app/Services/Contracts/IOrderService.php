<?php

namespace App\Services\Contracts;

interface IOrderService
{
    public function getOrdersBySupplier(int $supplierId);
    public function getAll();
    public function getUserOrders($userId);
    public function updateOrderItemStatus(int $userId, int $orderId, int $orderItemId, string $status);
    public function createOrderFromCart(int $userId, float $totalPrice, string $status, string $paymentMethod);
    public function createOrderWithItems(int $userId, array $orderData, array $orderItems);
}
