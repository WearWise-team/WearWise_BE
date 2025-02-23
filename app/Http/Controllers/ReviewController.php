<?php

namespace App\Http\Controllers;

use App\Services\Contracts\IReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(IReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        $reviews = $this->reviewService->getAllReviews();
        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = $this->reviewService->createReview($validated);
        return response()->json($review, 201);
    }

    public function show($productId)
    {
        $review = $this->reviewService->getReviewById((int) $productId);
        return response()->json($review);
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = $this->reviewService->updateReview($validated, (int) $id);
        return response()->json($review);
    }

    public function destroy($id)
    {
        $this->reviewService->deleteReview((int) $id);
        return response()->json(null, 204);
    }
}
