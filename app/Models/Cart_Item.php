<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart_Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_color_id',
        'product_size_id',
        'quantity',
    ];
    public $timestamps = true;

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
