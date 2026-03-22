<?php

use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoopController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // User Management Routes
    Route::resource('users', UserController::class)->only(['store', 'update', 'destroy']);

    // Daily Reports
    Route::get('/daily-reports/export', [DailyReportController::class, 'export'])->name('daily-reports.export');
    Route::put('/daily-reports/{dailyReport}/approve', [DailyReportController::class, 'approve'])->name('daily-reports.approve');
    Route::put('/daily-reports/{dailyReport}/reject', [DailyReportController::class, 'reject'])->name('daily-reports.reject');
    Route::resource('daily-reports', DailyReportController::class)->names('daily-reports');

    // Coops & Farms
    Route::middleware(function ($request, $next) {
        if (!auth()->user()->canAccess('farms')) abort(403, 'Anda tidak memiliki hak akses ke halaman Master Peternakan.');
        return $next($request);
    })->group(function () {
        Route::resource('farms', \App\Http\Controllers\FarmController::class);
    });

    Route::middleware(function ($request, $next) {
        if (!auth()->user()->canAccess('coops')) abort(403, 'Anda tidak memiliki hak akses ke halaman Master Kandang.');
        return $next($request);
    })->group(function () {
        Route::resource('coops', CoopController::class)->names('coops');
        Route::post('/coops/{coop}/snapshot', [CoopController::class, 'saveSnapshot'])->name('coops.snapshot');
    });

    // Supply & Stock Routes
    Route::middleware(function ($request, $next) {
        if (!auth()->user()->canAccess('supplies')) abort(403, 'Anda tidak memiliki hak akses ke halaman Master Inventaris.');
        return $next($request);
    })->group(function () {
        Route::get('/supplies/transactions', [SupplyController::class, 'transactions'])->name('supplies.transactions');
        Route::post('/supplies/{supply}/stock-in', [SupplyController::class, 'stockIn'])->name('supplies.stock-in');
        Route::post('/supplies/{supply}/stock-out', [SupplyController::class, 'stockOut'])->name('supplies.stock-out');
        Route::resource('supplies', SupplyController::class)->names('supplies');
    });
});