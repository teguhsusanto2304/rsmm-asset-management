<?php

use Illuminate\Support\Facades\Route; // ...existing code...
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetController;

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

Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('assets', AssetController::class)->except(['destroy']);
    Route::get('assets/{asset}/label', [AssetController::class, 'label'])->name('assets.label');
    Route::get('assets/test', function () {
        dd('test');
    })->name('assets.test');
    Route::get('assets/import', [AssetController::class, 'import'])->name('assets.import');
    Route::post('assets/import', [AssetController::class, 'processImport'])->name('assets.import.process');
    Route::get('assets/template/download', [AssetController::class, 'downloadTemplate'])->name('assets.template.download');
});

