<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestPanelController;
use App\Http\Controllers\Api\MarkerController;
use App\Http\Controllers\Api\UserTestEntryController;
use App\Http\Controllers\Api\UserMarkerValueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the Application's RouteServiceProvider and
| assigned to the "api" middleware group. Enjoy building your API!
|
*/

// Provided by Laravel Breeze API for authentication
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Test Panels (read-only)
    Route::get('/test-panels', [TestPanelController::class, 'index']);

    // Markers (read-only)
    Route::get('/markers', [MarkerController::class, 'index']);
    Route::get('/markers/{marker}', [MarkerController::class, 'show']); // To get details including healthy ranges

    // User Test Entries (CRUD for the authenticated user's entries)
    Route::post('/user-test-entries', [UserTestEntryController::class, 'store']);
    Route::get('/user-test-entries', [UserTestEntryController::class, 'index']);
    Route::get('/user-test-entries/{userTestEntry}', [UserTestEntryController::class, 'show']);

    // User Marker Values (specific endpoint for charting historical data)
    // Note: The Marker model is passed for convenient eager loading in the controller
    Route::get('/user-marker-values/by-marker/{marker}', [UserMarkerValueController::class, 'getByMarker']);
});