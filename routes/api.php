<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShopRequestController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::post('/guestbook', [GuestbookController::class, 'store']);
Route::get('/guestbook', [GuestbookController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{cart}', [CartController::class, 'update']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);

    // Feedback
    Route::post('/feedback', [FeedbackController::class, 'store']);

    // Shop Requests
    Route::post('/shop-requests', [ShopRequestController::class, 'store']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Categories
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Products
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Customers
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/{user}', [CustomerController::class, 'show']);
        Route::delete('/customers/{user}', [CustomerController::class, 'destroy']);

        // Guestbook
        Route::delete('/guestbook/{guestbook}', [GuestbookController::class, 'destroy']);

        // Orders
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

        // Shop Requests
        Route::get('/shop-requests', [ShopRequestController::class, 'index']);
        Route::put('/shop-requests/{shopRequest}/status', [ShopRequestController::class, 'updateStatus']);
    });
});
