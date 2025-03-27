<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'description',
        'start_date',
        'end_date',
        'percentage',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_assignments', 'discount_id', 'product_id')
        ->withTimestamps();
    }
}