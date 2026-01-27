<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\JobRoleController;
use App\Http\Controllers\Admin\AttendanceSettingController;
use App\Http\Controllers\AttendanceController;
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
    Route::get('/admin/users/approvals', [UserController::class, 'approvals'])->name('users.approvals');
    Route::post('/admin/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/admin/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    Route::resource('admin/users', UserController::class)->names('users');

    // Division & Job Role Management
    Route::resource('admin/divisions', DivisionController::class)->names('divisions')->except(['show', 'create', 'edit']);
    Route::resource('admin/job-roles', JobRoleController::class)->names('job-roles')->except(['show', 'create', 'edit']);

    // Attendance Management (Admin) - REMOVED: No approval workflow needed
    // Attendance Settings
    Route::get('/admin/attendance/settings', [AttendanceSettingController::class, 'index'])->name('admin.attendance.settings');
    Route::post('/admin/attendance/settings', [AttendanceSettingController::class, 'update'])->name('admin.attendance.settings.update');
    Route::get('/admin/attendance/settings/get-all', [AttendanceSettingController::class, 'getAll'])->name('admin.attendance.settings.all');

    // Attendance Records
    Route::get('/admin/attendance/records', [AttendanceSettingController::class, 'records'])->name('admin.attendance.records');
});

// Attendance Routes (Peserta Magang)
Route::middleware(['auth', 'peserta_magang'])->group(function () {
    Route::get('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance/checkin', [AttendanceController::class, 'storeCheckin'])->name('attendance.store-checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'storeCheckout'])->name('attendance.store-checkout');
    Route::post('/attendance/manual-checkout', [AttendanceController::class, 'storeManualCheckout'])->name('attendance.store-manual-checkout');
    Route::get('/attendance/manual', [AttendanceController::class, 'manualCheckin'])->name('attendance.manual');
    Route::post('/attendance/manual', [AttendanceController::class, 'storeManualCheckin'])->name('attendance.store-manual');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/today-status', [AttendanceController::class, 'getTodayStatus'])->name('attendance.today-status');
});


// User Routes
Route::middleware(['auth'])->group(function () {
    // tambahkan route user lainnya di sini
});
