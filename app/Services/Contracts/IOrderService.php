<?php

namespace App\Services\Contracts;

interface IOrderService
{
    public function getAllOrders();
    public function getOrderById(int $id);
    public function createOrder(array $data);
    public function updateOrder(array $data, int $id);
    public function deleteOrder(int $id);
}
?>