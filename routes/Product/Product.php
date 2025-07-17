<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;


// Products
Route::get('/product-list', [ProductController::class, 'productList'])->name('product-list')->middleware('permission:product-list');
Route::get('/product-update-page', [ProductController::class, 'updateProductPage'])->name('product-update-page')->middleware('permission:product-update-page');
Route::post('/update-product', [ProductController::class, 'updateProduct'])->name('update-product')->middleware('permission:update-product');
