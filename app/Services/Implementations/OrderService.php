<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IOrderRepository;

class OrderService
{
    protected $orderRepository;

    public function __construct(IOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders()
    {
        return $this->orderRepository->getAll();
    }

    public function getOrderById(int $id)
    {
        return $this->orderRepository->findById($id);
    }

    public function createOrder(array $data)
    {
        return $this->orderRepository->create($data);
    }

    public function updateOrder(int $id, array $data)
    {
        return $this->orderRepository->update($id, $data);
    }

    public function deleteOrder(int $id)
    {
        return $this->orderRepository->delete($id);
    }
}