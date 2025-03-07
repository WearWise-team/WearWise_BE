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
        $orderItemId = $data['order_item_id']; // Lấy order_item_id từ request

        // 1. Kiểm tra xem order_item có hợp lệ không (tồn tại, thuộc về user và đơn hàng đã hoàn thành)
        $orderItem = Order_Item::where('id', $orderItemId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'completed'); // Chỉ lấy đơn hàng đã hoàn thành
            })
            ->first();

        if (!$orderItem) {
            return response()->json(['error' => 'Invalid order item or order not completed.'], 400);
        }

        // Lấy product_id từ order_item
        $productId = $orderItem->product_id;

        // 2. Kiểm tra xem người dùng đã đánh giá sản phẩm này từ đơn hàng này chưa
        $reviewExists = Review::where('user_id', $userId)
                            ->where('order_item_id', $orderItemId) // Kiểm tra theo từng order item
                            ->exists();

        if ($reviewExists) {
            return response()->json(['error' => 'You have already reviewed this product from this order.'], 400);
        }

        // 3. Tạo review
        $review = Review::create([
            'user_id'       => $userId,
            'product_id'    => $productId, // Lấy từ order_item
            'order_item_id' => $orderItemId,
            'rating'        => $data['rating'],
            'content'       => $data['content'],
        ]);

        return response()->json($review, 201); // Trả về review đã tạo
    }




}

?>