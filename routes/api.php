<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pembeli\CartController;
use App\Http\Controllers\Pembeli\MenuController;
use App\Http\Controllers\Pembeli\ReviewController;
use App\Http\Controllers\Penjual\DessertController;
use App\Http\Controllers\Penjual\PesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Modern REST API Routes
|--------------------------------------------------------------------------
*/

// Auth API
Route::post('/login', [AuthController::class, 'login']);

// Admin API
Route::prefix('admin')->group(function () {
    Route::middleware('api_key')->group(function () {
        Route::get('/users', [UserController::class, 'apiGet']);
        Route::post('/users/login', [UserController::class, 'apiLogin']);
    });

    Route::get('/validasi', [ValidationController::class, 'apiGet']);
    Route::post('/validasi', [ValidationController::class, 'apiStore']);
    Route::put('/validasi', [ValidationController::class, 'apiUpdate']);
    Route::delete('/validasi/{id?}', [ValidationController::class, 'apiDestroy']);
});

// Penjual API
Route::prefix('penjual')->middleware('api_key')->group(function () {
    Route::get('/dessert', [DessertController::class, 'index']);
    Route::post('/dessert', [DessertController::class, 'store']);
    Route::put('/dessert', [DessertController::class, 'update']);
    Route::delete('/dessert/{id?}', [DessertController::class, 'destroy']);

    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::post('/pesanan', [PesananController::class, 'store']);
    Route::put('/pesanan', [PesananController::class, 'update']);
    Route::delete('/pesanan/{id?}', [PesananController::class, 'destroy']);
});

// Pembeli API
Route::prefix('pembeli')->middleware('api_key')->group(function () {
    Route::match(['get', 'post'], '/produk', [MenuController::class, 'apiGetOrSearch']);

    Route::get('/cart', [CartController::class, 'apiGet']);
    Route::post('/cart', [CartController::class, 'apiStore']);
    Route::put('/cart', [CartController::class, 'apiUpdate']);
    Route::delete('/cart/{id?}', [CartController::class, 'apiDestroy']);

    Route::get('/ulasan', [ReviewController::class, 'apiGet']);
    Route::post('/ulasan', [ReviewController::class, 'apiStore']);
    Route::put('/ulasan', [ReviewController::class, 'apiUpdate']);
    Route::delete('/ulasan/{id?}', [ReviewController::class, 'apiDestroy']);
});
