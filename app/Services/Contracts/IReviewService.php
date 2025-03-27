<?php

namespace App\Services\Contracts;

interface IReviewService
{
    public function createReview(array $data);
    public function getReviewedProductsBySupplier(int $userId);
}