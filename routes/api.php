<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MoMoController;
use App\Http\Controllers\VirtualTryOnController;
use App\Http\Controllers\KlingAIController;
use App\Http\Controllers\TryOnKController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WishlistController;

// Product routes
Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::post('/products/{id}', [ProductController::class, 'updateProduct']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::post('/products/search', [ProductController::class, 'searchProductByName']);
Route::get('/products/more/{id}', [ProductController::class, 'getProductDetails']);
Route::post('/products/filter', [ProductController::class, 'filterProduct']);
Route::get('/productswithsize', [ProductController::class, 'getProductWithColorAndSize']);
Route::get('/productsbysupplierID/{id}', [ProductController::class, 'getProductBySupplierID']);
Route::post('/products/upload', [ProductController::class, 'uploadImages']);
Route::patch('/products/{id}/restore', [ProductController::class, 'restoreProduct']);

// User routes
Route::apiResource('users', controller: UserController::class);
Route::get("/getAllUsersIsDeleted", [UserController::class, "getUsersIsDeleted"]);
Route::put("/restoreUser/{id}", [UserController::class, "restoreUser"]);

// Auth routes
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    // Route::post('refresh', 'AuthController@refresh');
    Route::get('profile', [AuthController::class, 'me']);
});

// Color routes
Route::prefix('colors')->group(function () {
    Route::get('/', [ColorController::class, 'index']);
    Route::get('/{id}', [ColorController::class, 'show']);
    Route::post('/', [ColorController::class, 'store']); 
    Route::put('/{id}', [ColorController::class, 'update']); 
    Route::delete('/{id}', [ColorController::class, 'destroy']);
});

// Momo Service
Route::post('/momo/payment', [MoMoController::class, 'createPayment']);
Route::middleware('auth:api')->group(function () {
    Route::get('/myCart/{user_id}', [CartController::class, 'getCartItemsByUserId']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove', [CartController::class, 'removeCartItem']);
});

// Order routes
Route::middleware('auth:api')->group(function () {
    Route::get('/orders/{userId}', [OrderController::class, 'index']);
    Route::post('/orders/{userId}', [OrderController::class, 'store']);
    Route::put('/orders', [OrderController::class, 'updateOrderStatus']);
    Route::post('/products/create-order', [OrderController::class, 'createOrderWithItems']);
    Route::post('/virtual-tryon-klingAI', [KlingAIController::class, 'tryOnClothesWithKling']);
    Route::post('/get-result-try-on/{taskId}', [TryOnKController::class, 'getTryOnResult']);

    Route::prefix('reviews')->group(function () {
        Route::post('/', [ReviewController::class, 'store']);
    });
    
    Route::prefix('wishlists')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/', [WishlistController::class, 'createWishlist']);
        Route::delete('/{productId}', [WishlistController::class, 'destroyWishlist']);
    });
});

// tryon routes
Route::post('/virtual-tryon', [VirtualTryOnController::class, 'tryOnClothes']);

Route::post('/get-token', [KlingAIController::class, 'generateToken']);

// Size routes
Route::prefix('sizes')->group(function () {
    Route::get('/', [SizeController::class, 'index']);
});


// Supplier routes
Route::prefix('suppliers')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::get('/getSupplierByUserID/{user_id}', [SupplierController::class, 'getSupplierByUserID']);
});

Route::prefix('discounts')->group(function () {
    Route::get('/', [DiscountController::class, 'index']);
});