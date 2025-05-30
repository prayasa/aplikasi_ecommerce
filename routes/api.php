<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderItemsController;

// Route untuk login dan logout tanpa proteksi auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Semua route API resource dilindungi middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', CustomersController::class);
    Route::apiResource('categories', CategoriesController::class);
    Route::apiResource('products', ProductsController::class);
    Route::apiResource('orders', OrdersController::class);
    Route::apiResource('order-items', OrderItemsController::class);
});
