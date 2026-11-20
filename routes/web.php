<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pembeli\CartController;
use App\Http\Controllers\Pembeli\MenuController;
use App\Http\Controllers\Pembeli\ReviewController;
use App\Http\Controllers\Penjual\DessertController;
use App\Http\Controllers\Penjual\PenjualController;
use App\Http\Controllers\Penjual\PesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Monolithic Views & Backward-Compatible Endpoints)
|--------------------------------------------------------------------------
*/

// Root Redirect to Login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication (View & Endpoint)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login.html', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login.php', [AuthController::class, 'login']);

// ==========================================
// ROLE: ADMIN (Monolithic CRUD & APIs)
// ==========================================
Route::prefix('admin')->group(function () {
    // Monolithic User Management
    Route::get('/users', function (\Illuminate\Http\Request $request) {
        if ($request->has('delete_id')) {
            return app(UserController::class)->destroy($request);
        }
        return app(UserController::class)->index();
    })->name('admin.users');

    Route::get('/user_admin.php', function (\Illuminate\Http\Request $request) {
        if ($request->has('delete_id')) {
            return app(UserController::class)->destroy($request);
        }
        return app(UserController::class)->index();
    });

    Route::post('/users', [UserController::class, 'store']);
    Route::post('/user_admin.php', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Monolithic Product Validation
    Route::get('/validasi', function (\Illuminate\Http\Request $request) {
        if ($request->has('delete_id')) {
            return app(ValidationController::class)->destroy($request);
        }
        return app(ValidationController::class)->index();
    })->name('admin.validasi');

    Route::get('/validasi.php', function (\Illuminate\Http\Request $request) {
        if ($request->has('delete_id')) {
            return app(ValidationController::class)->destroy($request);
        }
        return app(ValidationController::class)->index();
    });

    Route::post('/validasi', [ValidationController::class, 'store']);
    Route::post('/validasi.php', [ValidationController::class, 'store']);
    Route::delete('/validasi/{id}', [ValidationController::class, 'destroy']);

    // Admin APIs (Legacy endpoints compatibility)
    Route::middleware('api_key')->group(function () {
        Route::get('/user_api.php', [UserController::class, 'apiGet']);
        Route::post('/user_api.php', [UserController::class, 'apiLogin']);
    });

    Route::get('/validasi_api.php', [ValidationController::class, 'apiGet']);
    Route::post('/validasi_api.php', [ValidationController::class, 'apiStore']);
    Route::put('/validasi_api.php', [ValidationController::class, 'apiUpdate']);
    Route::delete('/validasi_api.php', [ValidationController::class, 'apiDestroy']);
});

// ==========================================
// ROLE: PENJUAL (Frontend & APIs)
// ==========================================
Route::prefix('penjual')->group(function () {
    // Frontend Dashboard
    Route::get('/', [PenjualController::class, 'index'])->name('penjual.index');
    Route::get('/index.html', [PenjualController::class, 'index']);

    // Penjual Dessert API (Legacy compatibility)
    Route::middleware('api_key')->group(function () {
        Route::get('/dessert_api.php', [DessertController::class, 'index']);
        Route::post('/dessert_api.php', [DessertController::class, 'store']);
        Route::put('/dessert_api.php', [DessertController::class, 'update']);
        Route::delete('/dessert_api.php', [DessertController::class, 'destroy']);

        // Penjual Pesanan API (Legacy compatibility)
        Route::get('/pesanan_api.php', [PesananController::class, 'index']);
        Route::post('/pesanan_api.php', [PesananController::class, 'store']);
        Route::put('/pesanan_api.php', [PesananController::class, 'update']);
        Route::delete('/pesanan_api.php', [PesananController::class, 'destroy']);
    });
});

// ==========================================
// ROLE: PEMBELI (Frontend & APIs)
// ==========================================
Route::prefix('pembeli')->group(function () {
    // Frontend Views
    Route::get('/menu', [MenuController::class, 'index'])->name('pembeli.menu');
    Route::get('/menu.php', [MenuController::class, 'index']);

    Route::get('/keranjang', [CartController::class, 'index'])->name('pembeli.keranjang');
    Route::get('/Keranjang.php', [CartController::class, 'index']);

    Route::get('/ulasan', [ReviewController::class, 'index'])->name('pembeli.ulasan');
    Route::get('/ulasan.html', [ReviewController::class, 'index']);

    // Pembeli APIs (Legacy compatibility)
    Route::middleware('api_key')->group(function () {
        Route::match(['get', 'post'], '/produk_api.php', [MenuController::class, 'apiGetOrSearch']);

        Route::get('/cart_api.php', [CartController::class, 'apiGet']);
        Route::post('/cart_api.php', [CartController::class, 'apiStore']);
        Route::put('/cart_api.php', [CartController::class, 'apiUpdate']);
        Route::delete('/cart_api.php', [CartController::class, 'apiDestroy']);

        Route::get('/ulasan_api.php', [ReviewController::class, 'apiGet']);
        Route::post('/ulasan_api.php', [ReviewController::class, 'apiStore']);
        Route::put('/ulasan_api.php', [ReviewController::class, 'apiUpdate']);
        Route::delete('/ulasan_api.php', [ReviewController::class, 'apiDestroy']);
    });
});
