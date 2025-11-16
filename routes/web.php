<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\CategoryWebController;
use App\Http\Controllers\Web\ProductWebController;
use App\Http\Controllers\Web\CartWebController;
use App\Http\Controllers\Web\OrderWebController;
use App\Http\Controllers\Web\GuestbookWebController;
use App\Http\Controllers\Web\ProfileWebController;
use App\Http\Controllers\Web\AdminController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories', [CategoryWebController::class, 'index'])->name('categories.index');
Route::get('/products', [ProductWebController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductWebController::class, 'show'])->name('products.show');
Route::get('/guestbook', [GuestbookWebController::class, 'index'])->name('guestbook.index');
Route::post('/guestbook', [GuestbookWebController::class, 'store'])->name('guestbook.store');

// Auth routes
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileWebController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileWebController::class, 'update'])->name('profile.update');
    
    Route::get('/cart', [CartWebController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartWebController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartWebController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartWebController::class, 'remove'])->name('cart.remove');
    
    Route::get('/checkout', [OrderWebController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderWebController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderWebController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderWebController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderWebController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{order}/cancel', [OrderWebController::class, 'cancel'])->name('orders.cancel');
    
    Route::post('/shop-requests', [ProfileWebController::class, 'storeShopRequest'])->name('shop-requests.store');
    
    Route::post('/feedback', [App\Http\Controllers\Web\FeedbackWebController::class, 'store'])->name('feedback.store');
});

// Seller routes
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Web\SellerController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/products', [App\Http\Controllers\Web\SellerController::class, 'products'])->name('products');
    Route::get('/products/create', [App\Http\Controllers\Web\SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Web\SellerController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Web\SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Web\SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Web\SellerController::class, 'destroyProduct'])->name('products.destroy');
    
    Route::get('/orders', [App\Http\Controllers\Web\SellerController::class, 'orders'])->name('orders');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Web\SellerController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    Route::get('/settings', [App\Http\Controllers\Web\SellerController::class, 'shopSettings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Web\SellerController::class, 'updateShopSettings'])->name('settings.update');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
    Route::get('/customers/{user}', [AdminController::class, 'showCustomer'])->name('customers.show');
    Route::delete('/customers/{user}', [AdminController::class, 'destroyCustomer'])->name('customers.destroy');
    
    Route::get('/categories/create', [CategoryWebController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryWebController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryWebController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryWebController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryWebController::class, 'destroy'])->name('categories.destroy');
    
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [ProductWebController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductWebController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductWebController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductWebController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductWebController::class, 'destroy'])->name('products.destroy');
    
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    Route::delete('/guestbook/{guestbook}', [GuestbookWebController::class, 'destroy'])->name('guestbook.destroy');
    
    Route::get('/shop-requests', [AdminController::class, 'shopRequests'])->name('shop-requests.index');
    Route::post('/shop-requests/{shopRequest}/approve', [AdminController::class, 'approveShopRequest'])->name('shop-requests.approve');
    Route::post('/shop-requests/{shopRequest}/reject', [AdminController::class, 'rejectShopRequest'])->name('shop-requests.reject');
});
