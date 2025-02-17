<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'role',
        'weight',
        'height',
        'shirt_size',
        'pant_size',
        'gender'
    ];

    protected $hidden = ['password'];

    public function wishlist()
    {
        return $this-> hasMany(Wishlist::class);
    }

    public function order()
    {
        return $this-> hasMany(Order::class);
    }

    public function review()
    {
        return $this-> hasMany(Review::class);
    }

    public function cart()
    {
        return $this-> hasMany(Cart::class);
    }
    
}