<?php

use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MemberAuthController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/ping', fn () => response()->json(['status' => 'ok']));

    Route::prefix('member')->group(function (): void {
        Route::post('/register', [MemberAuthController::class, 'register']);
        Route::post('/login', [MemberAuthController::class, 'login']);

        Route::middleware('auth:api')->group(function (): void {
            Route::post('/logout', [MemberAuthController::class, 'logout']);
            Route::get('/profile', [MemberAuthController::class, 'profile']);
        });
    });

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/brands', [BrandController::class, 'index']);
});
