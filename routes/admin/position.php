<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;

Route::get('/position/lists', [PositionController::class, 'lists']);
Route::post('/position/create', [PositionController::class, 'create']);
Route::post('/position/update', [PositionController::class, 'update']);
Route::post('/position/delete', [PositionController::class, 'delete']);
