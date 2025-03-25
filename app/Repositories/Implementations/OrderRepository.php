<?php

namespace App\Repositories\Implementations;

use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Supplier;
use App\Repositories\Contracts\IOrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements IOrderRepository
{

    public function getAll()
    {
        return Order::with('order_items')->get();
    }

    public function getOrdersBySupplier(int $userId)
    {
        $supplier = Supplier::where('user_id', $userId)->first();
        if (!$supplier) {
            return collect();
        }
        return Order::select('orders.*', 'users.name as user_name', 'users.email as user_email')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('colors', 'order_items.product_color_id', '=', 'colors.id')
            ->join('sizes', 'order_items.product_size_id', '=', 'sizes.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('products.supplier_id', $supplier->id)
            ->groupBy('orders.id')
            ->with(['order_items' => function ($query) {
                $query->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('colors', 'order_items.product_color_id', '=', 'colors.id')
                    ->join('sizes', 'order_items.product_size_id', '=', 'sizes.id')
                    ->select([
                        'order_items.order_id',
                        'order_items.id as order_item_id',
                        'order_items.quantity',
                        'order_items.status',
                        'products.id as product_id',
                        'products.name as product_name',
                        'products.main_image',
                        'products.price',
                        'colors.id as color_id',
                        'colors.name as color_name',
                        'sizes.id as size_id',
                        'sizes.name',
                        'sizes.shirt_size',
                        'sizes.pant_size',
                        DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.order_item_id = order_items.id) as reviewed')
                    ]);
            }])
            ->get();
    }

    public function getUserOrders($userId)
    {
        $orders = DB::table('orders')
            ->where('user_id', $userId)
            ->get();

        $orderItems = DB::table('orders as o')
            ->leftJoin('order_items as oi', 'o.id', '=', 'oi.order_id')
            ->leftJoin('products as p', 'oi.product_id', '=', 'p.id')
            ->leftJoin('colors as col', 'oi.product_color_id', '=', 'col.id')
            ->leftJoin('sizes as s', 'oi.product_size_id', '=', 's.id')
            ->leftJoin('reviews as r', 'oi.id', '=', 'r.order_item_id') // Kiểm tra review
            ->where('o.user_id', $userId)
            ->select(
                'o.id as order_id',
                'oi.id as order_item_id',
                'oi.quantity',
                'oi.status',
                'p.id as product_id',
                'p.name as product_name',
                'p.main_image',
                'p.price',
                'col.id as color_id',
                'col.name as color_name',
                's.id as size_id',
                's.name',
                's.shirt_size',
                's.pant_size',
                DB::raw('CASE WHEN r.id IS NOT NULL THEN true ELSE false END as reviewed') // Kiểm tra review
            )
            ->get();

        $groupedOrderItems = $orderItems->groupBy('order_id');

        $orders->transform(function ($order) use ($groupedOrderItems) {
            $order->items = $groupedOrderItems[$order->id] ?? [];
            return $order;
        });

        return $orders;
    }

    public function createOrder(array $data, $userId)
    {
        $data['user_id'] = $userId;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $orderId = DB::table('orders')->insertGetId($data);

        return DB::table('orders')->where('id', $orderId)->first();
    }

    public function updateOrderItemStatus(int $userId, int $orderId, int $orderItemId, string $status)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.id', $orderItemId)
            ->where('order_items.order_id', $orderId)
            ->where('orders.user_id', $userId)
            ->update([
                'order_items.status' => $status,
                'order_items.updated_at' => now()
            ]);
    }

    public function createOrderWithItems(int $userId, array $orderData, array $orderItems)
    {
        return DB::transaction(function () use ($userId, $orderData, $orderItems) {
            // Tạo Order
            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $orderData['total_amount'] ?? 0,
                'payment_method' => $orderData['payment_method'] ?? 'COD',
                'order_date' => now()
            ]);

            // Tạo Order Items
            foreach ($orderItems as $item) {
                Order_Item::create([
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'status' => $item['status'] ?? 'pending',
                    'total_price' => $item['total_price'],
                    'product_color_id' => $item['product_color_id'],
                    'product_size_id' => $item['product_size_id'],
                    'product_id' => $item['product_id']
                ]);
            }

            return $order;
        });
    }
}