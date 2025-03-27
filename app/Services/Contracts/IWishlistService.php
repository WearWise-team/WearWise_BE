<?php

namespace App\Services\Contracts;

interface IWishlistService
{
    public function getAllWishlists(int $userId);
    public function createWishlist(int $userId,array $data);
    public function deleteWishlist(int $userId, int $productId);
}