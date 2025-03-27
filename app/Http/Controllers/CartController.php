<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveCartItemRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\Contracts\ICartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(ICartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCartItemsByUserId($user_id)
    {
        return $this->cartService->getCartItemsByUserId($user_id);
    }

    public function addToCart(Request $request)
    {
        $data = $request->all();

        if (!isset($data['product_id']) || !is_numeric($data['product_id'])) {
            return response()->json(['error' => 'Product ID is required and must be a number.'], 422);
        }

        if (!isset($data['product_color_id']) || !is_numeric($data['product_color_id'])) {
            return response()->json(['error' => 'Product Color ID is required and must be a number.'], 422);
        }

        if (!isset($data['product_size_id']) || !is_numeric($data['product_size_id'])) {
            return response()->json(['error' => 'Product Size ID is required and must be a number.'], 422);
        }

        if (!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            return response()->json(['error' => 'Quantity is required and must be greater than 0.'], 422);
        }

        // Nếu dữ liệu hợp lệ, gọi service
        return $this->cartService->addToCart(
            auth()->id(),
            $data['product_id'],
            $data['product_color_id'],
            $data['product_size_id'],
            $data['quantity']
        );
    }
    public function updateCart(UpdateCartRequest $request)
    {
        $validated = $request->validated();
        return $this->cartService->updateCartItem(
            auth()->id(),
            $validated['cart_item_id'],
            $validated['quantity']
        );
    }
    public function removeCartItem(RemoveCartItemRequest $request)
    {
        $validated = $request->validated();
        return $this->cartService->removeCartItem(
            auth()->id(),
            $validated['cart_item_id']
        );
    }
}
