<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'order_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order_items() {
        return $this->hasMany(Order_Item::class);
    }
}