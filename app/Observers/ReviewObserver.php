<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    public function created(Review $review)
    {
        $review->product->updateRatingAvg();
    }

    public function updated(Review $review)
    {
        $review->product->updateRatingAvg();
    }

    public function deleted(Review $review)
    {
        $review->product->updateRatingAvg();
    }
}
