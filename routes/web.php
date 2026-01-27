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
    Route::resource('admin/users', UserController::class)->names('users');

    Route::get('/admin/users/approvals', [UserController::class, 'approvals'])->name('users.approvals');
    Route::post('/admin/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/admin/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    // tambahkan route admin lainnya di sini
});

// User Routes
Route::middleware(['auth'])->group(function () {
    // tambahkan route user lainnya di sini
});
