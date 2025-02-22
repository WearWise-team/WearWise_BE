<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Color extends Model
{
    protected $table = 'product_colors';

    protected $fillable = [
        'product_id',
        'color_id'
    ];

    public $timestamps = true;
}