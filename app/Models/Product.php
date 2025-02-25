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
        'description',
        'price',
        'image',
        'quantity',
        'category',
        'supplier_id',
        'rating_avg'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart_item()
    {
        return $this->belongsTo(Cart_Item::class);
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

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')
            ->withTimestamps();
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id')
            ->withTimestamps();
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function updateRatingAvg()
    {
        $avgRating = $this->reviews()->avg('rating');

        $this->update(['rating_avg' => $avgRating]);
    }
}
