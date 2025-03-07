<?php

namespace App\Repositories\Contracts;

interface IWishlistRepository
{
    public function getAll(int $userId);
    public function create(int $userId, array $data);
    public function delete(int $userId,int $productId);
}