<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Properties
    Route::resource('properties', PropertyController::class);

    // Property GeoJSON for map display
    Route::get('/properties/geojson', [PropertyController::class, 'getGeoJson'])->name('properties.geojson');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/properties/export', [PropertyExportController::class, 'exportToZip'])
        ->name('properties.export');
});

// API Routes for AJAX
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Location management
    Route::get('/mahallas', [LocationController::class, 'getMahallas'])->name('api.mahallas.index');
    Route::get('/streets', [LocationController::class, 'getStreets'])->name('api.streets.index');
    Route::post('/mahallas', [LocationController::class, 'storeMahalla'])->name('api.mahallas.store');
    Route::post('/streets', [LocationController::class, 'storeStreet'])->name('api.streets.store');

    // STIR/PINFL validation
    Route::post('/validate-stir-pinfl', [PropertyController::class, 'validateStirPinfl'])->name('api.validate-stir-pinfl');
});

require __DIR__ . '/auth.php';
