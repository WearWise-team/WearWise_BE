<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'main_image',
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

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function updateRatingAvg()
    {
        $avgRating = $this->reviews()->avg('rating');

        $this->update(['rating_avg' => $avgRating]);
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                // Nếu xóa vĩnh viễn, xóa hoàn toàn khỏi DB
                $product->sizes()->detach();
                $product->colors()->detach();
                $product->discounts()->detach();
                $product->images()->forceDelete();
            } else {
                DB::table('product_sizes')
                    ->where('product_id', $product->id)
                    ->update(['product_sizes.deleted_at' => now()]);

                DB::table('product_colors')
                    ->where('product_id', $product->id)
                    ->update(['product_colors.deleted_at' => now()]);

                DB::table('discount_assignments')
                    ->where('product_id', $product->id)
                    ->update(['discount_assignments.deleted_at' => now()]);

                $product->images()->update(['deleted_at' => now()]);
            }
        });

        static::restoring(function ($product) {
            DB::table('product_sizes')
                ->where('product_id', $product->id)
                ->update(['product_sizes.deleted_at' => null]);

            DB::table('product_colors')
                ->where('product_id', $product->id)
                ->update(['product_colors.deleted_at' => null]);

            DB::table('discount_assignments')
                ->where('product_id', $product->id)
                ->update(['discount_assignments.deleted_at' => null]);

            $product->images()->update(['deleted_at' => null]);
        });
    }
}