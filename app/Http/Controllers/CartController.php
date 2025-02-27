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
    public function addToCart(AddToCartRequest $request)
    {
        $validated = $request -> validated();
        return $this->cartService->addToCart(auth()->id(),
            $validated['product_id'], 
            $validated['product_color_id'], 
            $validated['product_size_id'],
            $validated['quantity']
        );
    }
    public function updateCart(UpdateCartRequest $request)
    {
        $validated = $request->validated();
        return $this->cartService->updateCartItem(auth()->id(),
            $validated['cart_item_id'],
            $validated['quantity']
        );
    }
    public function removeCartItem(RemoveCartItemRequest $request)
    {
        $validated = $request->validated();
        return $this->cartService->removeCartItem(auth()->id(),
            $validated['cart_item_id']
        );
    }

}