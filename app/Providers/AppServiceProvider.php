<?php

namespace App\Providers;

use App\Repositories\Contracts\IAuthRepository;
use App\Repositories\Contracts\ICart_ItemRepository;
use App\Repositories\Contracts\ICartRepository;
use App\Repositories\Contracts\IDiscount_AssignmentRepository;
use App\Repositories\Contracts\IDiscountRepository;
use App\Repositories\Contracts\IOrder_ItemRepository;
use App\Repositories\Contracts\IOrderRepository;
use App\Repositories\Contracts\IProductRepository;
use App\Repositories\Implementations\OrderRepository;
use App\Repositories\Implementations\WishlistRepository;
use App\Services\Contracts\IReviewService;
use App\Services\Contracts\ISizeService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\IReviewRepository;
use App\Repositories\Contracts\ISizeRepository;
use App\Repositories\Contracts\ISupplierRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IWishlistRepository;
use App\Repositories\Implementations\AuthRepository;
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
use App\Models\Review;
use App\Observers\ReviewObserver;
use App\Repositories\Contracts\IColorRepository;
use App\Repositories\Implementations\ColorRepository;
use App\Services\Contracts\ICartService;
use App\Services\Contracts\IColorService;
use App\Services\Contracts\IDiscountService;
use App\Services\Contracts\IOrderService;
use App\Services\Contracts\IProductService;
use App\Services\Contracts\ISupplierService;
use App\Services\Contracts\IUserService;
use App\Services\Contracts\IWishlistService;
use App\Services\Implementations\CartService;
use App\Services\Implementations\ColorService;
use App\Services\Implementations\DiscountService;
use App\Services\Implementations\OrderService;
use App\Services\Implementations\ProductService;
use App\Services\Implementations\ReviewService;
use App\Services\Implementations\SizeService;
use App\Services\Implementations\SupplierService;
use App\Services\Implementations\UserService;
use App\Services\Implementations\WishlistService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ISupplierRepository::class, SupplierRepository::class);
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(IReviewRepository::class, ReviewRepository::class);
        $this->app->bind(IWishlistRepository::class, WishlistRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(ICartRepository::class, CartRepository::class);
        $this->app->bind(IDiscountRepository::class, DiscountRepository::class);
        $this->app->bind(IOrder_ItemRepository::class, Order_ItemRepository::class);
        $this->app->bind(ICart_ItemRepository::class, Cart_ItemRepository::class);
        $this->app->bind(IDiscount_AssignmentRepository::class, Discount_AssignmentRepository::class);
        $this->app->bind(ISizeRepository::class, SizeRepository::class);
        $this->app->bind(IColorRepository::class, ColorRepository::class);

        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(ISupplierService::class, SupplierService::class);
        $this->app->bind(IProductService::class, ProductService::class);
        $this->app->bind(IReviewService::class, ReviewService::class);
        $this->app->bind(IOrderService::class, OrderService::class);
        $this->app->bind(ICartService::class, CartService::class);
        $this->app->bind(IWishlistService::class, WishlistService::class);
        $this->app->bind(IDiscountService::class, DiscountService::class);
        $this->app->bind(ISizeService::class, SizeService::class);
        $this->app->bind(IColorService::class, ColorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Review::observe(ReviewObserver::class);
    }
}
