<?php

namespace App\Repositories\Implementations;

use App\Models\Order;
use App\Repositories\Contracts\IOrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements IOrderRepository
{
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
            ->where('o.user_id', $userId)
            ->select(
                'o.id as order_id',
                'oi.id as order_item_id',
                'oi.quantity',
                'oi.status',
                'p.id as product_id',
                'p.name as product_name',
                'p.image',
                'p.price',
                'col.id as color_id',
                'col.name as color_name',
                's.id as size_id',
                's.shirt_size',
                's.pant_size'
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

    public function updateOrderStatus(int $userId, int $orderId, string $status)
    {
        return DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->update(['status' => $status, 'updated_at' => now()]);
    }
}
