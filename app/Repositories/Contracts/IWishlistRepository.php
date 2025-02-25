<?php

namespace App\Repositories\Contracts;

interface IWishlistRepository
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function delete(int $id);
}