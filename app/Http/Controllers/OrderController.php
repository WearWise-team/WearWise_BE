<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\IOrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(IOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index($userId)
    {
        return response()->json($this->orderService->getUserOrders($userId));
    }

    public function store(Request $request, $userId): JsonResponse
    {
        $validatedData = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,cancelled',
            'payment_method' => 'required|string|in:cod,momo,vnpay',
        ]);

        // Truyền `total_amount` vào service
        $order = $this->orderService->createOrderFromCart($userId, $validatedData['total_amount'], $validatedData['status'], $validatedData['payment_method']);

        if (!$order) {
            return response()->json(['message' => 'Failed to create order'], 500);
        }

        return response()->json($order, 201);
    }

    public function updateOrderStatus(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'userId' => 'required|integer',
            'orderId' => 'required|integer',
            'status' => 'required|string|in:pending,completed,cancelled'
        ]);

        $updated = $this->orderService->updateOrderStatus($validatedData['userId'], $validatedData['orderId'], $validatedData['status']);

        if (!$updated) {
            return response()->json(['message' => 'Cập nhật trạng thái thất bại hoặc đơn hàng không tồn tại'], 400);
        }

        return response()->json(['message' => 'Cập nhật trạng thái đơn hàng thành công'], 200);
    }
}

?>