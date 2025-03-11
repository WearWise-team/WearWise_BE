<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MoMoController;
use App\Http\Controllers\VirtualTryOnController;
use App\Http\Controllers\KlingAIController;
use App\Http\Controllers\TryOnKController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WishlistController;

Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::post('/products/search', [ProductController::class, 'searchProductByName']);
Route::get('/products/more/{id}', [ProductController::class, 'getProductDetails']);
Route::post('/products/filter', [ProductController::class, 'filterProduct']);

Route::apiResource('users', controller: UserController::class);
Route::get("/getAllUsersIsDeleted", [UserController::class, "getUsersIsDeleted"]);
Route::put("/restoreUser/{id}", [UserController::class, "restoreUser"]);

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

Route::prefix('colors')->group(function () {
    Route::get('/', [ColorController::class, 'index']); // Lấy danh sách màu
    Route::get('/{id}', [ColorController::class, 'show']); // Lấy chi tiết màu theo ID
    Route::post('/', [ColorController::class, 'store']); // Tạo mới một màu
    Route::put('/{id}', [ColorController::class, 'update']); // Cập nhật màu
    Route::delete('/{id}', [ColorController::class, 'destroy']); // Xóa màu
});

Route::post('/momo/payment', [MoMoController::class, 'createPayment']);
Route::middleware('auth:api')->group(function () {
    Route::get('/myCart/{user_id}', [CartController::class, 'getCartItemsByUserId']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove', [CartController::class, 'removeCartItem']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/orders/{userId}', [OrderController::class, 'index']);
    Route::post('/orders/{userId}', [OrderController::class, 'store']);
    Route::put('/orders', [OrderController::class, 'updateOrderStatus']);
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

Route::post('/virtual-tryon', [VirtualTryOnController::class, 'tryOnClothes']);

Route::post('/get-token', [KlingAIController::class, 'generateToken']);