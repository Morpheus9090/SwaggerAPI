<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceItemController;

Route::get('/invoiceitem/lists', [InvoiceItemController::class, 'lists']);
Route::post('/invoiceitem/create', [InvoiceItemController::class, 'create']);
Route::post('/invoiceitem/update', [InvoiceItemController::class, 'update']);
Route::post('/invoiceitem/delete', [InvoiceItemController::class, 'delete']);
