<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/produk', [App\Http\Controllers\HomeController::class, 'produk'])->name('products.index');

// Admin Routes
Route::middleware(['isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/users', UserController::class);
    // tambahkan route admin lainnya di sini
});

// User Routes
Route::middleware(['auth'])->group(function () {
    // tambahkan route user lainnya di sini
});
