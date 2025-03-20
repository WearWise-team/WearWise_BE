<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface IOrderRepository
{
    public function getOrdersBySupplier(int $supplierId);
    public function getAll();
    public function getUserOrders($userId);
    public function createOrder(array $data, $userId);
    public function updateOrderItemStatus(int $userId, int $orderId, int $orderItemId, string $status);
    public function createOrderWithItems(int $userId, array $orderData, array $orderItems);
}
