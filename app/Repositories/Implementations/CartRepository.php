<?php

namespace App\Repositories\Implementations;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
use App\Repositories\Contracts\ICartRepository;

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