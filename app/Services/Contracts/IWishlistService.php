<?php

namespace App\Services\Contracts;

interface IWishlistService
{
    public function getAllWishlists();
    public function getWishlistById(int $id);
    public function createWishlist(array $data);
    public function deleteWishlist(int $id);
}