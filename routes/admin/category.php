<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/category/lists', [CategoryController::class, 'lists']);
Route::post('/category/create', [CategoryController::class, 'create']);
Route::post('/category/update', [CategoryController::class, 'update']);
Route::post('/category/delete', [CategoryController::class, 'delete']);
