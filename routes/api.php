<?php

use App\Http\Controllers\Api\V1\BrandAdminController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryAdminController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MemberAuthController;
use App\Http\Controllers\Api\V1\ProductAdminController;
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

    Route::prefix('admin')->middleware(['auth:admin-api', 'admin.guard'])->group(function (): void {
        Route::prefix('products')->group(function (): void {
            Route::get('/', [ProductAdminController::class, 'index']);
            Route::post('/', [ProductAdminController::class, 'store']);
            Route::get('/{id}', [ProductAdminController::class, 'show']);
            Route::put('/{id}', [ProductAdminController::class, 'update']);
            Route::delete('/{id}', [ProductAdminController::class, 'destroy']);
        });

        Route::prefix('categories')->group(function (): void {
            Route::get('/', [CategoryAdminController::class, 'index']);
            Route::post('/', [CategoryAdminController::class, 'store']);
            Route::get('/{id}', [CategoryAdminController::class, 'show']);
            Route::put('/{id}', [CategoryAdminController::class, 'update']);
            Route::delete('/{id}', [CategoryAdminController::class, 'destroy']);
        });

        Route::prefix('brands')->group(function (): void {
            Route::get('/', [BrandAdminController::class, 'index']);
            Route::post('/', [BrandAdminController::class, 'store']);
            Route::get('/{id}', [BrandAdminController::class, 'show']);
            Route::put('/{id}', [BrandAdminController::class, 'update']);
            Route::delete('/{id}', [BrandAdminController::class, 'destroy']);
        });
    });
});
