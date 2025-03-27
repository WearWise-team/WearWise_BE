<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\IReviewRepository;
use App\Services\Contracts\IReviewService;

class ReviewService implements IReviewService
{
    protected $reviewRepository;

    public function __construct(IReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function createReview(array $data)
    {
        return $this->reviewRepository->create($data);
    }

    public function getReviewedProductsBySupplier(int $userId) {
        return $this->reviewRepository->getReviewedProductsBySupplier($userId);
    }
}