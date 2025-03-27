<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_colors';

    protected $fillable = [
        'product_id',
        'color_id'
    ];

    public $timestamps = true;
}