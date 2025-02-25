<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;

Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::post('/products/search', [ProductController::class, 'searchProductByName']);
Route::get('/products/more/{id}', [ProductController::class, 'getProductDetails']);
Route::post('/products/filter', [ProductController::class, 'filterProduct']);


Route::apiResource('users', controller: UserController::class);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::match(['get', 'post'], 'login', [AuthController::class, 'login']);
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