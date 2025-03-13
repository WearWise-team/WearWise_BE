<?php

namespace App\Services\Implementations;

use App\Services\Contracts\IOrderService;
use App\Repositories\Contracts\IOrderRepository;
use App\Repositories\Contracts\ICartRepository;
use App\Repositories\Contracts\IOrder_ItemRepository;
use Illuminate\Support\Facades\DB;

class OrderService implements IOrderService
{
    protected $orderRepository;
    protected $cartRepository;
    protected $orderItemRepository;

    public function __construct(
        IOrderRepository $orderRepository,
        ICartRepository $cartRepository,
        IOrder_ItemRepository $orderItemRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getUserOrders($userId)
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    public function createOrderFromCart(int $userId, float $totalPrice, string $status, string $paymentMethod)
    {
        return DB::transaction(function () use ($userId, $totalPrice, $status, $paymentMethod) {
            $cartItems = $this->cartRepository->getCartItemsByUserId($userId);

            if (empty($cartItems['cart'])) {
                return response()->json(['message' => 'Giỏ hàng trống!'], 400);
            }
            
            $order = $this->orderRepository->createOrder([
                'user_id' => $userId,
                'total_amount' => $totalPrice,
                'payment_method' => $paymentMethod,
                'order_date' => now()
            ], $userId);

            foreach ($cartItems['cart'] as $item) {
                $this->orderItemRepository->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                    'status' => $status,
                    'total_price' => $item['total_price'],
                    'product_color_id' => $item['color']['id'],
                    'product_size_id' => $item['size']['id']
                ]);
            }

            $this->cartRepository->clearUserCart($userId);

            return $order;
        });
    }

    public function updateOrderStatus(int $userId, int $orderId, string $status)
    {
        return $this->orderRepository->updateOrderStatus($userId, $orderId, $status);
    }

    public function createOrderWithItems(int $userId, array $orderData, array $orderItems) {
        return $this->orderRepository->createOrderWithItems($userId, $orderData, $orderItems);
    }
}
