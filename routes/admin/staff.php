<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

Route::get('/staff/lists', [StaffController::class, 'lists']);
Route::post('/staff/create', [StaffController::class, 'create']);
Route::post('/staff/update', [StaffController::class, 'update']);
Route::post('/staff/delete', [StaffController::class, 'delete']);
