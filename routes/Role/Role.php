<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;


// Roles
Route::get('/list-role', [RoleController::class, 'roleList'])->name('list-role')->middleware('permission:list-role');
Route::get('/role-save-page', [RoleController::class, 'roleSavePage'])->name('role-save-page')->middleware('permission:role-save-page');
Route::post('/create-role', [RoleController::class, 'createRole'])->name('create-role')->middleware('permission:create-role');
Route::post('/update-role', [RoleController::class, 'updateRole'])->name('update-role')->middleware('permission:update-role');
Route::get('/delete-role', [RoleController::class, 'deleteRole'])->name('delete-role')->middleware('permission:delete-role');
