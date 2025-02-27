<?php

namespace App\Http\Controllers;

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
        $wishlists = $this->wishlistService->getAllWishlists();
        return response()->json([
            'success' => true,
            'message' => 'Show all wishlists successfully!',
            'data' => $wishlists,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
        ]);

        $wishlist = $this->wishlistService->createWishlist($validated);
        
        return $wishlist? response()->json([
            'success' => true,
            'message' => 'Create successfully!',
            'data' => $wishlist,
        ], 201): response()->json([
            'success' => false,
            'message' => 'add wishlist fail!',
            'data' => $wishlist,
        ], 400);
    }

    public function show($id)
    {
        $wishlist = $this->wishlistService->getWishlistById((int) $id);
        return response()->json($wishlist);
    }

    public function destroy($id)
    {
        $this->wishlistService->deleteWishlist((int) $id);
        return response()->json(null, 204);
    }
}
