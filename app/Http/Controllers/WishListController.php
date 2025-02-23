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
        return response()->json($wishlists);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        $wishlist = $this->wishlistService->createWishlist($validated);
        return response()->json($wishlist, 201);
    }

    public function show($id)
    {
        $wishlist = $this->wishlistService->getWishlistById((int) $id);
        return response()->json($wishlist);
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'integer',
        ]);

        $wishlist = $this->wishlistService->updateWishlist($validated, (int) $id);
        return response()->json($wishlist);
    }

    public function destroy($id)
    {
        $this->wishlistService->deleteWishlist((int) $id);
        return response()->json(null, 204);
    }
}
