<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Properties
    Route::resource('properties', PropertyController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes for AJAX
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/mahallas', [LocationController::class, 'getMahallas']);
    Route::get('/streets', [LocationController::class, 'getStreets']);
    Route::post('/mahallas', [LocationController::class, 'storeMahalla']);
    Route::post('/streets', [LocationController::class, 'storeStreet']);
});

require __DIR__.'/auth.php';
