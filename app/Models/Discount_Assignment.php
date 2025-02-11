<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount_Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'discount_assignments';
    
    protected $fillable = [
        'discount_id',
        'product_id'
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}