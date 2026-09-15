<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/product/{slug}', [LandingController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| User Self Profile Routes (Requires Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Customer Cart Routes (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
    Route::get('/checkout-whatsapp', [CartController::class, 'whatsappCheckout'])->name('whatsapp');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Requires Auth & Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Admin only)
    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/toggle-block', [AdminUserController::class, 'toggleBlock'])->name('users.toggle-block');

    // Products Management
    Route::resource('products', AdminProductController::class);
    
    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Hero Banner Sliders Management
    Route::resource('sliders', SliderController::class);

    // Financial Bookkeeping & Sales Reports
    Route::get('/financial/download-report', [FinancialController::class, 'downloadReport'])->name('financial.download');
    Route::patch('/financial/{financial}/update-status', [FinancialController::class, 'updateStatus'])->name('financial.update-status');
    Route::resource('financial', FinancialController::class);
    
    // Store Settings & Logo Upload
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
