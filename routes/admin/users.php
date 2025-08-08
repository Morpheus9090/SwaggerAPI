<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users/lists', [UserController::class, 'lists']);
Route::post('/users/create', [UserController::class, 'create']);
Route::post('/users/update', [UserController::class, 'update']);
Route::post('/users/delete', [UserController::class, 'delete']);
