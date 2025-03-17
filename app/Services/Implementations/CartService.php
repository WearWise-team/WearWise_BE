<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ICartRepository;
use App\Services\Contracts\ICartService;
use Illuminate\Http\JsonResponse;

class CartService implements ICartService
{
    protected $cartRepository;

    public function __construct(ICartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getAllCarts()
    {
        return $this->cartRepository->getAll();
    }

    public function getCartById(int $id)
    {
        return $this->cartRepository->findById($id);
    }

    public function createCart(array $data)
    {
        return $this->cartRepository->create($data);
    }

    public function updateCart(array $data, int $id)
    {
        return $this->cartRepository->update($id, $data);
    }

    public function deleteCart(int $id)
    {
        return $this->cartRepository->delete($id);
    }

    public function getUserCart($userId)
    {
        return $this->cartRepository->getUserCart($userId);
    }

    public function getCartItemsByUserId($userId)
    {
        return $this->cartRepository->getCartItemsByUserId($userId);
    }

    public function addToCart($userId, $productId, $productColorId, $productSizeId, $quantity): JsonResponse
    {
        $cart = $this->cartRepository->getUserCart($userId);
        if (!$cart) {
            $cart = $this->cartRepository->createCartForUser($userId);
        }
        $cartItem = $this->cartRepository->findCartItem($cart->id, $productId, $productColorId, $productSizeId);
        if ($cartItem) {
            $this->cartRepository->updateCartItemQuantity($cartItem, $quantity);
        } else {
            $this->cartRepository->addNewCartItem($cart->id, $productId, $productColorId, $productSizeId, $quantity);
        }

        return response()->json(['message' => 'The product has been added to your cart.'], 200);
    }

    public function updateCartItem($userId, $cartItemId, $quantity): JsonResponse
    {
        $cartItem = $this->cartRepository->findCartItemById($cartItemId);
        if (!$cartItem || $cartItem->cart->user_id !== $userId) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $this->cartRepository->updateCartItemQuantityExact($cartItem, $quantity);
        return response()->json(['message' => 'Cart item quantity updated successfully.'], 200);
    }

    public function removeCartItem($userId, $cartItemId): JsonResponse
    {
        $cartItem = $this->cartRepository->findCartItemByUserId($userId, $cartItemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Product not found in cart.'], 404);
        }

        $this->cartRepository->removeCartItem($cartItem);

        return response()->json(['message' => 'The product has been removed from your cart.'], 200);
    }
}
