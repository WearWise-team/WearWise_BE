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

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getAllOrders();
        return response()->json($orders);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order = $this->orderService->createOrder($validatedData);
        return response()->json($order, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'customer_name' => 'sometimes|string|max:255',
            'total_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,completed,cancelled',
        ]);

        $order = $this->orderService->updateOrder($validatedData, $id);

        if (!$order) {
            return response()->json(['message' => 'Order not found or update failed'], 404);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->orderService->deleteOrder($id);

        if (!$deleted) {
            return response()->json(['message' => 'Order not found or delete failed'], 404);
        }

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
