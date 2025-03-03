<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Services\Contracts\IWishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(IWishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $userId = auth()->id();
        return response()->json($this->wishlistService->getAllWishlists($userId), 200);
    }

    public function createWishlist(WishlistRequest $request)
    {
        $userId = auth()->id();
        $validated = $request -> validated();
        $result = $this->wishlistService->createWishlist($userId,$validated);
        if ($result === false) {
            return response()->json(['message' => 'Product removed from wishlist'], 200);
        }
        
        return response()->json($result, 201);
    }

    public function destroyWishlist($producId)
    {
        $userId = auth()->id();
        return response()->json(['success' => $this->wishlistService->deleteWishlist($userId,$producId)], 200);
    }
}
