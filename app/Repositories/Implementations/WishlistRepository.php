<?php

namespace App\Repositories\Implementations;

use App\Models\Wishlist;
use App\Repositories\Contracts\IWishlistRepository;
use Illuminate\Support\Facades\Auth; 

class WishlistRepository implements IWishlistRepository
{
    protected $model;

    public function __construct(Wishlist $model)
    {
        $this->model = $model;
    }

    
    public function getAll(int $userId)
    {
        return Wishlist::where('user_id', $userId)->with('product')->get();
    }

    public function create(int $userId, array $data)
    {
        $existingWishlist = Wishlist::where('user_id', $userId)->where('product_id', $data['product_id'])->first();
        if ($existingWishlist) {
            $existingWishlist->delete();
            return false;
        }
        return 
             Wishlist::firstOrCreate([
                  'user_id'    => $userId,
                  'product_id' => $data['product_id'],
             ]);
    }

    public function delete(int $userId,int $productId)
    {
        $existingWishlist = Wishlist::where('user_id',$userId)->where('product_id', $productId)->first();
        if ($existingWishlist) {
            return $existingWishlist->delete();
        }
        return false;
    }
}
