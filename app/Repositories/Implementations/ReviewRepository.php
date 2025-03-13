<?php

namespace App\Repositories\Implementations;

use App\Models\Review;
use App\Models\Order_Item;
use App\Repositories\Contracts\IReviewRepository;
use Illuminate\Support\Facades\DB;
class ReviewRepository implements IReviewRepository
{
    protected $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        $userId = $data['user_id'];
        $orderItemId = $data['order_item_id'];

        $orderItem = Order_Item::where('id', $orderItemId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'completed'); 
            })
            ->first();

        if (!$orderItem) {
            return response()->json(['error' => 'Invalid order item or order not completed.'], 400);
        }

        $productId = $orderItem->product_id;

        $reviewExists = Review::where('user_id', $userId)
        ->where('order_item_id', $orderItemId)
        ->exists();

        if ($reviewExists) {
            return response()->json(['error' => 'You have already reviewed this product from this order.'], 400);
        }

        $review = Review::create([
            'user_id'       => $userId,
            'product_id'    => $productId, 
            'order_item_id' => $orderItemId,
            'rating'        => $data['rating'],
            'content'       => $data['content'],
        ]);

        return response()->json($review, 201);
    }
}
?>