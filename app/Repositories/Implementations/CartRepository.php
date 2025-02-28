<?php

namespace App\Repositories\Implementations;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
use App\Repositories\Contracts\ICartRepository;
use Illuminate\Support\Facades\DB;

class CartRepository implements ICartRepository
{
    protected $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $post = $this->model->find($id);
        return $post ? $post->update($data) : null;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    public function getUserCart($userId)
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function getCartItemsByUserId($userId)
    {
        $cartItems = DB::table('cart_items as ci')
            ->join('carts as c', 'ci.cart_id', '=', 'c.id')
            ->join('products as p', 'ci.product_id', '=', 'p.id')
            ->join('product_colors as pc', 'ci.product_color_id', '=', 'pc.id')
            ->join('colors as col', 'pc.color_id', '=', 'col.id')
            ->join('product_sizes as ps', 'ci.product_size_id', '=', 'ps.id')
            ->join('sizes as s', 'ps.size_id', '=', 's.id')
            ->where('c.user_id', $userId)
            ->select(
                'ci.id as cart_item_id',
                'ci.quantity',
                'p.id as product_id',
                'p.name as product_name',
                'p.description',
                'p.price',
                'p.image',
                'col.id as color_id',
                'col.name as color_name',
                'col.code as color_code',
                's.id as size_id',
                's.shirt_size',
                's.pant_size',
                's.minimun_weight',
                's.maximun_weight',
                's.minimun_height',
                's.maximun_height',
                's.target_audience'
            )
            ->get();
        // Nhóm các sản phẩm theo product_id
        $result = [
            'user_id' => $userId,
            'cart_items' => []
        ];
        foreach ($cartItems as $item) {
            // Kiểm tra xem sản phẩm đã tồn tại trong danh sách chưa
            if (!isset($result['cart_items'][$item->product_id])) {
                $result['cart_items'][$item->product_id] = [
                    'product' => [
                        'id' => $item->product_id,
                        'name' => $item->product_name,
                        'description' => $item->description,
                        'price' => $item->price,
                        'image' => $item->image,
                    ],
                    'colors' => [],
                    'sizes' => [],
                    'quantities' => [],
                ];
            }
            // Thêm màu sắc vào danh sách (nếu chưa có)
            $existingColors = array_column($result['cart_items'][$item->product_id]['colors'], 'id');
            if (!in_array($item->color_id, $existingColors)) {
                $result['cart_items'][$item->product_id]['colors'][] = [
                    'id' => $item->color_id,
                    'name' => $item->color_name,
                    'code' => $item->color_code
                ];
            }
            // Thêm size vào danh sách (nếu chưa có)
            $existingSizes = array_column($result['cart_items'][$item->product_id]['sizes'], 'id');
            if (!in_array($item->size_id, $existingSizes)) {
                $result['cart_items'][$item->product_id]['sizes'][] = [
                    'id' => $item->size_id,
                    'shirt_size' => $item->shirt_size,
                    'pant_size' => $item->pant_size,
                    'minimun_weight' => $item->minimun_weight,
                    'maximun_weight' => $item->maximun_weight,
                    'minimun_height' => $item->minimun_height,
                    'maximun_height' => $item->maximun_height,
                    'target_audience' => $item->target_audience
                ];
            }
            // Gán số lượng theo từng biến thể màu + size
            $result['cart_items'][$item->product_id]['quantities'][] = [
                'color' => $item->color_name,
                'size' => $item->shirt_size,
                'quantity' => $item->quantity
            ];
        }
        // Chuyển `cart_items` từ mảng có key `product_id` thành danh sách
        $result['cart_items'] = array_values($result['cart_items']);

        return $result;
    }


    public function createCartForUser($userId)
    {
        return Cart::create(['user_id' => $userId]);
    }

    public function findCartItem($cartId, $productId, $productColorId, $productSizeId)
    {
        return Cart_Item::where([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_color_id' => $productColorId,
            'product_size_id' => $productSizeId,
        ])->first();
    }

    public function addNewCartItem($cartId, $productId, $productColorId, $productSizeId, $quantity)
    {
        return Cart_Item::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_color_id' => $productColorId,
            'product_size_id' => $productSizeId,
            'quantity' => $quantity,
        ]);
    }

    public function findCartItemById($cartItemId)
    {
        return Cart_Item::find($cartItemId);
    }

    public function updateCartItemQuantity($cartItem, $quantity)
    {
        $cartItem->increment('quantity', $quantity);
    }

    public function updateCartItemQuantityExact($cartItem, $quantity)
    {
        $cartItem->update(['quantity' => $quantity]);
    }

    public function removeCartItem($cartItem)
    {
        $cartItem->delete();
    }

    // public function getProductStock($productId, $productColorId, $productSizeId)
    // {
    //     return Product::where('id', $productId)
    //         ->where('product_color_id', $productColorId)
    //         ->where('product_size_id', $productSizeId)
    //         ->value('quantity');
    // }
}
