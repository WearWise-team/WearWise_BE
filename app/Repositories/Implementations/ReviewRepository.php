<?php

namespace App\Repositories\Implementations;

use App\Models\Review;
use App\Models\Order_Item;
use App\Models\Product;
use App\Models\Supplier;
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

    public function getReviewedProductsBySupplier(int $userId)
    {
        $supplier = Supplier::where('user_id', $userId)->first();

        if (!$supplier) {
            return null;
        }

        return Product::where('supplier_id', $supplier->id)
            ->whereHas('reviews')
            ->with([
                'reviews' => function ($query) {
                    $query->select('id', 'product_id', 'user_id', 'rating', 'content', 'created_at')
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->get(['id', 'name', 'price', 'rating_avg']);
    }
}
