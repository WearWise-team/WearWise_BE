<?php

namespace App\Services\Contracts;

interface ICartService
{
    public function getAllCarts();
    public function getCartById(int $id);
    public function createCart(array $data);
    public function updateCart(array $data, int $id);
    public function deleteCart(int $id);
}