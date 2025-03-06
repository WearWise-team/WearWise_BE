<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Services\Contracts\IReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(IReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(ReviewRequest $request)
    {
        $validated = $request -> validated();
        $validated['user_id'] = auth()->id();
        $review = $this->reviewService->createReview($validated);
        return response()->json($review, 201);
    }

}
