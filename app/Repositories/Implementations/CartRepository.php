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
            ->join('colors as col', 'ci.product_color_id', '=', 'col.id')
            ->join('sizes as s', 'ci.product_size_id', '=', 's.id')
            ->leftJoin('discount_assignments as da', 'p.id', '=', 'da.product_id')
            ->leftJoin('discounts as d', 'da.discount_id', '=', 'd.id')
            ->where('c.user_id', $userId)
            ->whereNull('p.deleted_at') // Chỉ lấy sản phẩm chưa bị xóa mềm
            ->select(
                'ci.id as cart_item_id',
                'p.id as product_id',
                'p.name as product_name',
                'p.description',
                'p.price',
                'p.image',
                'ci.quantity',
                'ci.total_price',
                'col.id as color_id',
                'col.name as color_name',
                'col.code as color_code',
                's.id as size_id',
                's.shirt_size',
                's.pant_size',
                'd.id as discount_id',
                'd.code as discount_code',
                'd.description as discount_description',
                'da.start_date',
                'da.end_date',
                'da.percentage as discount_percentage'
            )
            ->get();

        $result = [
            'user_id' => $userId,
            'cart' => []
        ];
        
        foreach ($cartItems as $item) {
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            $existingIndex = array_search($item->cart_item_id, array_column($result['cart'], 'cart_item_id'));

            if ($existingIndex === false) {
                // Nếu chưa có, thêm sản phẩm mới vào giỏ hàng
                $cartItem = [
                    'cart_item_id' => $item->cart_item_id,
                    'product' => [
                        'id' => $item->product_id,
                        'name' => $item->product_name,
                        'description' => $item->description,
                        'price' => $item->price,
                        'image' => $item->image,
                    ],
                    'size' => [
                        'id' => $item->size_id,
                        'shirt_size' => $item->shirt_size,
                        'pant_size' => $item->pant_size
                    ],
                    'color' => [
                        'id' => $item->color_id,
                        'name' => $item->color_name,
                        'code' => $item->color_code
                    ],
                    'quantity' => $item->quantity,
                    'total_price' =>$item->total_price,
                    'discounts' => []
                ];

                // Nếu có giảm giá, thêm vào danh sách
                if ($item->discount_id) {
                    $cartItem['discounts'][] = [
                        'id' => $item->discount_id,
                        'code' => $item->discount_code,
                        'description' => $item->discount_description,
                        'percentage' => $item->discount_percentage,
                        'start_date' => $item->start_date,
                        'end_date' => $item->end_date
                    ];
                }

                $result['cart'][] = $cartItem;
            } else {
                // Nếu sản phẩm đã tồn tại, chỉ thêm giảm giá mới nếu chưa có
                if ($item->discount_id) {
                    $existingDiscounts = array_column($result['cart'][$existingIndex]['discounts'], 'id');
                    if (!in_array($item->discount_id, $existingDiscounts)) {
                        $result['cart'][$existingIndex]['discounts'][] = [
                            'id' => $item->discount_id,
                            'code' => $item->discount_code,
                            'description' => $item->discount_description,
                            'percentage' => $item->discount_percentage,
                            'start_date' => $item->start_date,
                            'end_date' => $item->end_date
                        ];
                    }
                }
            }
        }

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
        // Lấy thông tin sản phẩm từ product_id
        $product = Product::findOrFail($productId);

        return Cart_Item::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_color_id' => $productColorId,
            'product_size_id' => $productSizeId,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price, // Tính total_price
        ]);
    }


    public function findCartItemById($cartItemId)
    {
        return Cart_Item::with('cart')
            ->where('id', $cartItemId)
            ->first();
    }

    public function findCartItemByUserId($userId, $cartItemId)
    {
        return Cart_Item::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('id', $cartItemId)
            ->first();
    }

    public function updateCartItemQuantity($cartItem, $quantity)
    {
        $cartItem->increment('quantity', $quantity);
        $cartItem->update(['total_price' => $cartItem->quantity * $cartItem->product->price]);

    }

    public function updateCartItemQuantityExact($cartItem, $quantity)
    {
        $cartItem->update([
            'quantity' => $quantity,
            'total_price' => $quantity * $cartItem->product->price
        ]);
    }

    public function removeCartItem($cartItem)
    {
        if ($cartItem) {
            $cartItem->forceDelete();
        }
    }

    public function clearUserCart(int $userId)
    {
        $cartItems = Cart_Item::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        foreach ($cartItems as $item) {
            $item->forceDelete();
        }

        return $cartItems;
    }
}
