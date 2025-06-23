<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\OperationAreaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('operation-areas.index');
});

// Operation Areas Routes
Route::resource('operation-areas', OperationAreaController::class);

// Location API Routes for Cascading Dropdowns
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('provinces');
    Route::get('/districts/{province}', [LocationController::class, 'getDistrictsByProvince'])->name('districts');
    Route::get('/subdistricts/{district}', [LocationController::class, 'getSubdistrictsByDistrict'])->name('subdistricts');
    Route::get('/villages/{subdistrict}', [LocationController::class, 'getVillagesBySubdistrict'])->name('villages');
});
