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
        'category',
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

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items', 'product_id', 'cart_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_assignments', 'product_id', 'discount_id')
            ->withPivot('start_date', 'end_date', 'percentage')
            ->withTimestamps();
    }

    public function size()
    {
        return $this->hasMany(Size::class);
    }
    public function color()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id')
            ->withTimestamps();
    }
    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function product_variants() {
        return $this->hasOne(Product_Variants::class);
    }

}
