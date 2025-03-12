<?php

namespace App\Services\Contracts;

interface IOrderService
{
    public function getUserOrders($userId);
    public function updateOrderStatus(int $userId, int $orderId, string $status);
    public function createOrderFromCart(int $userId, float $totalPrice, string $status, string $paymentMethod);
    public function createOrderWithItems(int $userId, array $orderData, array $orderItems);
}
