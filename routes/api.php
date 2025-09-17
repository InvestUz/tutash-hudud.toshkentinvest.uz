<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/validate-stir-pinfl', [PropertyController::class, 'validateStirPinfl'])
    ->middleware(['auth:sanctum']);

// Area calculation (NEW)
Route::post('/calculate-area', [PropertyController::class, 'calculateArea'])
    ->middleware(['auth:sanctum']);

// Location management
Route::get('/mahallas', [LocationController::class, 'getMahallas']);
Route::post('/mahallas', [LocationController::class, 'storeMahalla'])
    ->middleware(['auth:sanctum']);

Route::get('/streets', [LocationController::class, 'getStreets']);
Route::post('/streets', [LocationController::class, 'storeStreet'])
    ->middleware(['auth:sanctum']);

// GeoJSON data for maps
Route::get('/properties/geojson', [PropertyController::class, 'getGeoJson'])
    ->middleware(['auth:sanctum']);
