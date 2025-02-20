<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\ICartRepository;

class CartService
{
    protected $cartRepository;

    public function __construct(ICartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getAllCarts()
    {
        return $this->cartRepository->getAll();
    }

    public function getCartById(int $id)
    {
        return $this->cartRepository->findById($id);
    }

    public function createCart(array $data)
    {
        return $this->cartRepository->create($data);
    }

    public function updateCart(int $id, array $data)
    {
        return $this->cartRepository->update($id, $data);
    }

    public function deleteCart(int $id)
    {
        return $this->cartRepository->delete($id);
    }
}