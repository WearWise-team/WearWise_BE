<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'quantity',
        'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart_items()
    {
        return $this->hasMany(Cart_Item::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function order_items()
    {
        return $this->hasMany(Order_Item::class);
    }

    public function discount_assignment()
    {
        return $this->hasMany(Discount_Assignment::class);
    }

    public function size()
    {
        return $this->hasMany(Size::class);
    }
}