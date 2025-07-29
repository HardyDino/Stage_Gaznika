<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\IotController;
use Illuminate\Support\Facades\Route;



Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/block', [UserController::class, 'block'])->name('users.block');

    Route::resource('clients', ClientController::class);
    Route::patch('clients/{client}/block', [ClientController::class, 'block'])->name('clients.block');
    Route::get('clients/{client}/history', [ClientController::class, 'history'])->name('clients.history');
    Route::get('clients/history/all', [ClientController::class, 'listhistorique'])->name('clients.listhistorique');

    Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/manage', [OrderController::class, 'manage'])->name('orders.manage');
    Route::get('/orders/geolocation', [OrderController::class, 'geolocation'])->name('orders.geolocation');

    Route::get('/collections/points', [CollectionController::class, 'points'])->name('collections.points');
    Route::get('/collections/schedule', [CollectionController::class, 'schedule'])->name('collections.schedule');

    Route::get('/analytics/stats', [AnalyticsController::class, 'stats'])->name('analytics.stats');
    Route::get('/analytics/ai', [AnalyticsController::class, 'ai'])->name('analytics.ai');

    Route::get('/iot/digesters', [IotController::class, 'digesters'])->name('iot.digesters');
    Route::get('/iot/maintenance', [IotController::class, 'maintenance'])->name('iot.maintenance');
});