<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderItemsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ====================================================================
// Rute Publik (Bisa diakses tanpa login)
// ====================================================================

// --- Autentikasi ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);

// --- Storefront ---
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);
Route::get('/categories', [CategoriesController::class, 'index']);


// ====================================================================
// Rute Terproteksi (Hanya untuk Admin yang sudah login)
// ====================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Manajemen Produk ---
    Route::post('/products', [ProductsController::class, 'store']);
    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::patch('/products/{id}', [ProductsController::class, 'update']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
    
    // --- Manajemen Lainnya ---
    Route::apiResource('customers', CustomersController::class);
    Route::apiResource('orders', OrdersController::class);
    Route::apiResource('order-items', OrderItemsController::class);
    Route::apiResource('categories', CategoriesController::class)->except(['index']); // GET (index) sudah publik
});