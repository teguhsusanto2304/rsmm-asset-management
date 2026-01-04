<?php

use Illuminate\Support\Facades\Route; // ...existing code...
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Forgot & Reset Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
});
Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('users', UserController::class);
});

// routes/web.php
Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('departments', DepartmentController::class);
});

Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('locations', LocationController::class);
});

Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('categories', CategoryController::class);
});

