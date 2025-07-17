<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;


// Users
Route::post('/create-user', [UserController::class, 'createUser'])->name('create-user')->middleware('permission:create-user');
Route::post('/update-user', [UserController::class, 'updateUser'])->name('update-user')->middleware('permission:update-user');
Route::get('/delete-user', [UserController::class, 'deleteUser'])->name('delete-user')->middleware('permission:delete-user');
Route::get('/list-user', [UserController::class, 'listUser'])->name('list-user')->middleware('permission:list-user');
Route::get('/user-save-page', [UserController::class, 'userSavePage'])->name('user-save-page')->middleware('permission:user-save-page');
