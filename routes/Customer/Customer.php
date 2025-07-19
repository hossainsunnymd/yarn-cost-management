<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;


// Customers
Route::get('/customer-list', [CustomerController::class, 'customerList'])->name('customer-list')->middleware('permission:customer-list');
Route::get('/customer-save-page', [CustomerController::class, 'customerSavePage'])->name('customer-save-page')->middleware('permission:customer-save-page');
Route::post('/create-customer', [CustomerController::class, 'createCustomer'])->name('create-customer')->middleware('permission:create-customer');
Route::post('/update-customer', [CustomerController::class, 'updateCustomer'])->name('update-customer')->middleware('permission:update-customer');
Route::get('/delete-customer', [CustomerController::class, 'deleteCustomer'])->name('delete-customer')->middleware('permission:delete-customer');


//customer payments
Route::get('/customer-payment-list', [CustomerController::class, 'customerPaymentList'])->name('customer-payment-list')->middleware('permission:customer-payment-list');
Route::post('/save-customer-payment', [CustomerController::class, 'saveCustomerPayment'])->name('save-customer-payment')->middleware('permission:save-customer-payment');
