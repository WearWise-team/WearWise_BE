<?php

namespace App\Repositories\Contracts;

interface IReviewRepository
{
    public function create(array $data);
    public function getReviewedProductsBySupplier(int $userId);
}