<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface IOrderRepository
{
    public function getAll();
    public function getUserOrders($userId);
    public function createOrder(array $data, $userId);
    public function updateOrderStatus(int $userId, int $orderId, string $status);
    public function createOrderWithItems(int $userId, array $orderData, array $orderItems);
}
