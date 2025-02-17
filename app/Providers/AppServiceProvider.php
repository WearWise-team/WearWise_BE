<?php

namespace App\Providers;

use App\Repositories\Implementations\OrderRepository;
use App\Repositories\Implementations\WishlistRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\IRepository;
use App\Repositories\Implementations\Cart_ItemRepository;
use App\Repositories\Implementations\CartRepository;
use App\Repositories\Implementations\Discount_AssignmentRepository;
use App\Repositories\Implementations\DiscountRepository;
use App\Repositories\Implementations\Order_ItemRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Implementations\ProductRepository;
use App\Repositories\Implementations\ReviewRepository;
use App\Repositories\Implementations\SizeRepository;
use App\Repositories\Implementations\SupplierRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IRepository::class, UserRepository::class);
        $this->app->bind(IRepository::class, SupplierRepository::class);
        $this->app->bind(IRepository::class, ProductRepository::class);
        $this->app->bind(IRepository::class, ReviewRepository::class);
        $this->app->bind(IRepository::class, WishlistRepository::class);
        $this->app->bind(IRepository::class, OrderRepository::class);
        $this->app->bind(IRepository::class, CartRepository::class);
        $this->app->bind(IRepository::class, DiscountRepository::class);
        $this->app->bind(IRepository::class, Order_ItemRepository::class);
        $this->app->bind(IRepository::class, Cart_ItemRepository::class);
        $this->app->bind(IRepository::class, Discount_AssignmentRepository::class);
        $this->app->bind(IRepository::class, SizeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}