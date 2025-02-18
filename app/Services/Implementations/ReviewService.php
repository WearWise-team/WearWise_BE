<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\IReviewRepository;

class ReviewService
{
    protected $reviewRepository;

    public function __construct(IReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllReviews()
    {
        return $this->reviewRepository->getAll();
    }

    public function getReviewById(int $id)
    {
        return $this->reviewRepository->findById($id);
    }

    public function createReview(array $data)
    {
        return $this->reviewRepository->create($data);
    }

    public function updateReview(int $id, array $data)
    {
        return $this->reviewRepository->update($id, $data);
    }

    public function deleteReview(int $id)
    {
        return $this->reviewRepository->delete($id);
    }
}