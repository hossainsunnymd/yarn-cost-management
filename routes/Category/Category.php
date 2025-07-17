<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\CategoryController;

// Category
Route::get('/list-category', [CategoryController::class, 'listCategory'])->name('list-category')->middleware('permission:list-category');
Route::get('/category-save-page', [CategoryController::class, 'categorySavePage'])->name('category-save-page')->middleware('permission:category-save-page');
Route::post('/create-category', [CategoryController::class, 'createCategory'])->name('create-category')->middleware('permission:create-category');
Route::post('/update-category', [CategoryController::class, 'updateCategory'])->name('update-category')->middleware('permission:update-category');
Route::get('/delete-category', [CategoryController::class, 'deleteCategory'])->name('delete-category')->middleware('permission:delete-category');
