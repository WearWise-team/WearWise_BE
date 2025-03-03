<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'quantity',
        'status',
        'product_color_id',
        'product_size_id',
        'product_id'
    ];
    public $timestamps = true;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
