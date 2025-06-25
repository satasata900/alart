<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\OperationAreaController;
use App\Http\Controllers\ResponsePointController;
// ResponseTeamMemberController removed
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('operation-areas.index');
});

// Operation Areas Routes
Route::resource('operation-areas', OperationAreaController::class);
// AJAX endpoint for observers province filter
Route::get('operation-areas/by-province/{province}', [OperationAreaController::class, 'byProvince'])->name('operation-areas.byProvince');

// Observers (راصدين) Routes
Route::resource('observers', \App\Http\Controllers\ObserverController::class);
Route::post('observers/{observer}/toggle', [\App\Http\Controllers\ObserverController::class, 'toggle'])->name('observers.toggle');
Route::get('observers/{observer}/activity', [\App\Http\Controllers\ObserverController::class, 'activity'])->name('observers.activity');
// AJAX endpoint for observers province filter
Route::get('observers/by-province/{province}', [\App\Http\Controllers\ObserverController::class, 'byProvince'])->name('observers.byProvince');

// Location API Routes for Cascading Dropdowns
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('provinces');
    Route::get('/districts/{province}', [LocationController::class, 'getDistrictsByProvince'])->name('districts');
    Route::get('/subdistricts/{district}', [LocationController::class, 'getSubdistrictsByDistrict'])->name('subdistricts');
    Route::get('/villages/{subdistrict}', [LocationController::class, 'getVillagesBySubdistrict'])->name('villages');
});

// Response Points (نقاط الاستجابة) Routes
Route::get('response-dashboard', [ResponsePointController::class, 'dashboard'])->name('response.dashboard');
Route::resource('response-points', ResponsePointController::class);
Route::post('response-points/{responsePoint}/toggle', [ResponsePointController::class, 'toggle'])->name('response-points.toggle');
