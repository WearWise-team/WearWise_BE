<?php

namespace App\Repositories\Contracts;

interface ICartRepository
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getUserCart($userId);
    public function createCartForUser($userId);
    public function findCartItem($cartId, $productId, $productColorId, $productSizeId);
    public function findCartItemById($cartItemId);
    public function updateCartItemQuantity($cartItem, $quantity);
    public function updateCartItemQuantityExact($cartItem, $quantity);
    public function addNewCartItem($cartId, $productId, $productColorId, $productSizeId, $quantity);
    public function removeCartItem($cartItem);
    // public function getProductStock($productId, $productColorId, $productSizeId);
    
}