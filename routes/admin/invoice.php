<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/invoice/lists', [InvoiceController::class, 'lists']);
Route::post('/invoice/create', [InvoiceController::class, 'create']);
Route::post('/invoice/update', [InvoiceController::class, 'update']);
Route::post('/invoice/delete', [InvoiceController::class, 'delete']);
