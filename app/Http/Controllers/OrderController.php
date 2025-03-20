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

    public function getOrdersBySupplier(int $userId)
    {
        $orders = $this->orderService->getOrdersBySupplier($userId);

        return response()->json($orders, 200);
    }

    public function getAll()
    {
        return response()->json($this->orderService->getAll());
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
            'orderItemId' => 'required|integer',
            'status' => 'required|string|in:pending,completed,cancelled'
        ]);

        $updated = $this->orderService->updateOrderItemStatus($validatedData['userId'], $validatedData['orderId'], $validatedData['orderItemId'], $validatedData['status']);

        if (!$updated) {
            return response()->json(['message' => 'Failed to update status or order does not exist'], 400);
        }

        return response()->json(['message' => 'Order status updated successfully'], 200);
    }

    public function createOrderWithItems(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required',
            'payment_method' => 'required|string',
            'order_items' => 'required|array',
            'order_items.*.quantity' => 'required',
            'order_items.*.total_price' => 'required',
            'order_items.*.product_color_id' => 'required|exists:product_colors,color_id',
            'order_items.*.product_size_id' => 'required|exists:product_sizes,size_id',
            'order_items.*.product_id' => 'required|exists:products,id',
        ]);

        $order = $this->orderService->createOrderWithItems(
            $validatedData['user_id'],
            [
                'total_amount' => $validatedData['total_amount'],
                'payment_method' => $validatedData['payment_method']
            ],
            $validatedData['order_items']
        );

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }
}
