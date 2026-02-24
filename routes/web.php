<?php

use Illuminate\Support\Facades\Route; // ...existing code...
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTransferRequestController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\TechnicianMaintenanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;

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
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('master-data')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('categories', CategoryController::class);
    
    // Permission Management
    Route::resource('permissions', PermissionController::class);
    
    // Role Management
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.show-permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    
    // Custom asset routes must come BEFORE resource route to prevent {asset} param matching
    Route::get('assets/import', [AssetController::class, 'import'])->name('assets.import');
    Route::post('assets/import', [AssetController::class, 'processImport'])->name('assets.import.process');
    Route::get('assets/template/download', [AssetController::class, 'downloadTemplate'])->name('assets.template.download');
    Route::get('assets/my-assets', [AssetController::class, 'myAssets'])->name('assets.my-assets');
    Route::get('assets/test', function () {
        dd('test');
    })->name('assets.test');
    
    Route::resource('assets', AssetController::class)->except(['destroy']);
    Route::get('assets/{asset}/label', [AssetController::class, 'label'])->name('assets.label');
    Route::get('assets/{asset}/assign', [AssetController::class, 'showAssign'])->name('assets.assign');
    Route::post('assets/{asset}/assign', [AssetController::class, 'processAssign'])->name('assets.assign.process');
    
    // Asset Transfer Request Routes
    Route::get('asset-transfers', [AssetTransferRequestController::class, 'index'])->name('asset-transfers.index');
    Route::get('asset-transfers/create', [AssetTransferRequestController::class, 'create'])->name('asset-transfers.create');
    Route::post('asset-transfers', [AssetTransferRequestController::class, 'store'])->name('asset-transfers.store');
    Route::get('asset-transfers/{assetTransfer}', [AssetTransferRequestController::class, 'show'])->name('asset-transfers.show');
    Route::post('asset-transfers/{assetTransfer}/approve', [AssetTransferRequestController::class, 'approve'])->name('asset-transfers.approve');
    Route::post('asset-transfers/{assetTransfer}/reject', [AssetTransferRequestController::class, 'reject'])->name('asset-transfers.reject');
    Route::post('asset-transfers/{assetTransfer}/complete', [AssetTransferRequestController::class, 'complete'])->name('asset-transfers.complete');
    Route::post('asset-transfers/{assetTransfer}/mark-returned', [AssetTransferRequestController::class, 'markReturned'])->name('asset-transfers.mark-returned');
    Route::delete('asset-transfers/{assetTransfer}', [AssetTransferRequestController::class, 'cancel'])->name('asset-transfers.cancel');
    
    // Maintenance Management Routes
    Route::get('maintenance/api/technicians', [MaintenanceController::class, 'getTechnicians'])->name('maintenance.api.technicians');
    Route::get('maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('maintenance/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('maintenance.edit');
    Route::put('maintenance/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenance.update');
    Route::post('maintenance/{maintenance}/assign', [MaintenanceController::class, 'assign'])->name('maintenance.assign');
    Route::post('maintenance/{maintenance}/start', [MaintenanceController::class, 'start'])->name('maintenance.start');
    Route::post('maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');
    Route::get('maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenance.show');
    
    // Maintenance Schedule Routes
    Route::resource('maintenance-schedule', MaintenanceScheduleController::class);
    //->except(['create']);
    Route::get('maintenance-schedule/create', [MaintenanceScheduleController::class, 'create'])->name('maintenance-schedule.create');
    Route::post('maintenance-schedule/{maintenanceSchedule}/pause', [MaintenanceScheduleController::class, 'pause'])->name('maintenance-schedule.pause');
    Route::post('maintenance-schedule/{maintenanceSchedule}/resume', [MaintenanceScheduleController::class, 'resume'])->name('maintenance-schedule.resume');

    // Technician Area Routes
    Route::prefix('technician')->group(function () {
        Route::get('/', [TechnicianMaintenanceController::class, 'dashboard'])->name('technician.dashboard');
        Route::get('/maintenance', [TechnicianMaintenanceController::class, 'maintenance'])->name('technician.maintenance');
        Route::get('/reports/{maintenance}', [TechnicianMaintenanceController::class, 'report'])->name('technician.report');
        Route::get('/statistics', [TechnicianMaintenanceController::class, 'statistics'])->name('technician.statistics');
    });
});

