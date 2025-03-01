<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_Size extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_sizes';

    protected $fillable = [
        'product_id',
        'size_id'
    ];

    public $timestamps = true;
}
