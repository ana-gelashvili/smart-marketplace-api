<?php

use App\Http\Controllers\Api\V1\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\BrandAdminController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryAdminController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MemberAuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProductAdminController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/ping', fn () => response()->json(['status' => 'ok']));

    Route::prefix('member')->group(function (): void {
        Route::post('/register', [MemberAuthController::class, 'register']);
        Route::post('/login', [MemberAuthController::class, 'login']);

        Route::middleware(['auth:member-api', 'abilities:member-api'])->group(function (): void {
            Route::post('/logout', [MemberAuthController::class, 'logout']);
            Route::get('/profile', [MemberAuthController::class, 'profile']);
        });
    });

    Route::middleware(['auth:member-api', 'abilities:member-api'])->prefix('cart')->group(function (): void {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/items', [CartController::class, 'addItem']);
        Route::put('/items/{id}', [CartController::class, 'updateItem']);
        Route::delete('/items/{id}', [CartController::class, 'removeItem']);
    });

    Route::middleware(['auth:member-api', 'abilities:member-api'])->prefix('orders')->group(function (): void {
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{uuid}', [OrderController::class, 'show']);
        Route::get('/{uuid}/payments', [PaymentController::class, 'indexForOrder']);
    });

    Route::middleware(['auth:member-api', 'abilities:member-api'])->prefix('payments')->group(function (): void {
        Route::post('/', [PaymentController::class, 'store']);
        Route::post('/{id}/success', [PaymentController::class, 'success']);
        Route::post('/{id}/failed', [PaymentController::class, 'failed']);
    });

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/brands', [BrandController::class, 'index']);

    Route::prefix('admin')->group(function (): void {
        Route::post('/login', [AdminAuthController::class, 'login']);

        Route::middleware(['auth:admin-api', 'abilities:admin-api'])->group(function (): void {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/profile', [AdminAuthController::class, 'profile']);

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
});
