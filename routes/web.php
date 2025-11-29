<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\LegacyDashboardController;
use App\Http\Controllers\PPPoEController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UseractiveController;
use Illuminate\Support\Facades\Route;

// Public routes (tidak perlu login)
Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/failed', function () {
    return view('failed');
})->name('failed.view');

// Protected routes - require authentication (menggunakan middleware auth default Laravel)
Route::middleware('auth')->group(function () {
    // Logout harus terautentikasi
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    // Dashboard baru (UI cyan) – menggunakan data dari LegacyDashboardController
    Route::get('dashboard', [LegacyDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard/data', [LegacyDashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // MikroTik Management
    Route::resource('mikrotik', \App\Http\Controllers\MikroTikController::class);
    Route::post('mikrotik/{id}/set-active', [\App\Http\Controllers\MikroTikController::class, 'setActive'])->name('mikrotik.set-active');
    Route::get('mikrotik/{id}/router-info', [\App\Http\Controllers\MikroTikController::class, 'getRouterInfo'])->name('mikrotik.router-info');

    // Interface
    Route::get('interface', [InterfaceController::class, 'index'])->name('interface.index');
    Route::get('interface/{interface}/traffic', [InterfaceController::class, 'traffic'])->name('interface.traffic');
    Route::get('interface/{interface}/traffic/api', [InterfaceController::class, 'trafficApi'])->name('interface.traffic.api');
    Route::post('interface/collect-all', [InterfaceController::class, 'collectAllTraffic'])->name('interface.collect-all');

    // PPPoE
    Route::get('pppoe/secret', [PPPoEController::class, 'secret'])->name('pppoe.secret');
    Route::get('pppoe/secret/active', [PPPoEController::class, 'active'])->name('pppoe.active');
    Route::post('pppoe/secret/add', [PPPoEController::class, 'add'])->name('pppoe.add');
    Route::get('pppoe/secret/edit/{id}', [PPPoEController::class, 'edit'])->name('pppoe.edit');
    Route::post('pppoe/secret/update', [PPPoEController::class, 'update'])->name('pppoe.update');
    Route::get('pppoe/secret/delete/{id}', [PPPoEController::class, 'delete'])->name('pppoe.delete');

    // Hotspot
    Route::get('hotspot/users', [HotspotController::class, 'users'])->name('hotspot.users');
    Route::get('hotspot/users/active', [HotspotController::class, 'active'])->name('hotspot.active');
    Route::post('hotspot/users/add', [HotspotController::class, 'add'])->name('hotspot.add');
    Route::get('hotspot/users/edit/{id}', [HotspotController::class, 'edit'])->name('hotspot.edit');
    Route::post('hotspot/users/update', [HotspotController::class, 'update'])->name('hotspot.update');

    // Realtime dashboard
    Route::get('dashboard/cpu', [LegacyDashboardController::class, 'cpu'])->name('dashboard.cpu');
    Route::get('dashboard/load', [LegacyDashboardController::class, 'load'])->name('dashboard.load');
    Route::get('dashboard/uptime', [LegacyDashboardController::class, 'uptime'])->name('dashboard.uptime');
    Route::get('dashboard/{traffic}', [LegacyDashboardController::class, 'traffic'])->name('dashboard.traffic');
    Route::get('dashboard/{traffic}/api', [LegacyDashboardController::class, 'apiTraffic'])->name('dashboard.traffic.api');

    // Report traffic
    Route::get('report-up', [ReportController::class, 'index'])->name('report-up.index');
    Route::get('report-up/load', [ReportController::class, 'load'])->name('report-up.load');
    Route::get('report-up/search', [ReportController::class, 'search'])->name('search.report');

    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // User active (MikroTik users)
    Route::get('useractive', [UseractiveController::class, 'index'])->name('user.index');
    Route::get('realtime/useractive', [UseractiveController::class, 'useractive'])->name('realtime.useractive');

    // Store data up & down
    Route::get('/up', [ReportController::class, 'up']);
    Route::get('/down', [ReportController::class, 'down']);
});
