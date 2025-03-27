<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sizes';

    protected $fillable = [
        'name',
        'shirt_size',
        'pant_size',
        'minimun_weight',
        'maximun_weight',
        'minimun_height',
        'maximun_height',
        'target_audience'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sizes', 'size_id', 'product_id')
            ->withTimestamps();
    }
}