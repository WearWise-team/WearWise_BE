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

    
    public function getAll()
    {
        $userId = Auth::id();
        return $userId
            ? $this->model->with('product')
                          ->where('user_id', $userId)
                          ->get()
            : collect(); 
    }

    public function findById(int $id)
    {
        return $this->model->where('user_id', Auth::id())
                           ->find($id);
    }

    public function create(array $data)
    {
        $userId = Auth::id();
        return $userId
            ? $this->model->firstOrCreate([
                  'user_id'    => $userId,
                  'product_id' => $data['product_id'],
              ])
            : null;
    }

    public function delete(int $id)
    {
        $userId = Auth::id();
        return $userId
            ? $this->model->where('user_id', $userId)
                          ->where('id', $id)
                          ->delete()
            : false;
    }
}
