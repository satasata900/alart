<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResponsePointController;

Route::get('operation-areas/{area}/response-points', [ResponsePointController::class, 'getByOperationArea']);
