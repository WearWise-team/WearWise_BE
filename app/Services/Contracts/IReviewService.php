<?php

namespace App\Services\Contracts;

interface IReviewService
{
    public function getAllReviews();
    public function getReviewById(int $id);
    public function createReview(array $data);
    public function updateReview(array $data, int $id);
    public function deleteReview(int $id);
}