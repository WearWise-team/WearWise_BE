<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    public function getRevenue(int $userId): JsonResponse
    {
        $orders = collect($this->orderService->getOrdersBySupplier($userId));

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push([
                'month_year' => Carbon::now()->subMonths($i)->format('Y-m'),
                'name' => Carbon::now()->subMonths($i)->format('M'),
                'total' => 0
            ]);
        }

        $filteredOrders = $orders->filter(function ($order) {
            return Carbon::parse($order->order_date) >= Carbon::now()->subMonths(5)->startOfMonth();
        });

        $revenues = $filteredOrders
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y-m');
            })
            ->map(function ($group) {
                return [
                    'total' => $group->sum('total_amount'),
                    'name' => Carbon::parse($group->first()->order_date)->format('M')
                ];
            });

        $finalData = $months->map(function ($month) use ($revenues) {
            if ($revenues->has($month['month_year'])) {
                $month['total'] = $revenues[$month['month_year']]['total'];
            }
            return $month;
        });
        return response()->json([
            'revenue' => $finalData
        ], 200);
    }
}