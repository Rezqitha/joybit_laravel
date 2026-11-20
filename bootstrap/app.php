<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'login.php',
            'login',
            'admin/user_api.php',
            'admin/validasi_api.php',
            'penjual/dessert_api.php',
            'penjual/pesanan_api.php',
            'pembeli/cart_api.php',
            'pembeli/produk_api.php',
            'pembeli/ulasan_api.php',
            'admin/user_admin.php',
            'admin/validasi.php',
            'api/*',
        ]);

        $middleware->alias([
            'api_key' => \App\Http\Middleware\ValidateApiKey::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->is('*_api.php') || $request->is('login.php') || $request->expectsJson(),
        );
    })->create();
