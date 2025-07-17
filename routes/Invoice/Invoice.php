<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoice\InvoiceController;


// Invoices
Route::get('/invoice-list', [InvoiceController::class, 'invoiceList'])->name('invoice-list')->middleware('permission:invoice-list');
Route::post('/create-invoice', [InvoiceController::class, 'createInvoice'])->name('create-invoice')->middleware('permission:create-invoice');
Route::get('/delete-invoice', [InvoiceController::class, 'deleteInvoice'])->name('delete-invoice')->middleware('permission:delete-invoice');


// Sale Page
Route::get('/sale-page', [InvoiceController::class, 'salePage'])->name('sale-page')->middleware('permission:sale-page');
