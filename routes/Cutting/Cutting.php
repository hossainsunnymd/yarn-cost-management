<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cutting\CuttingController;
use App\Http\Controllers\Cutting\CuttingPartyController;


// Cutting Party
Route::get('/cutting-party-list', [CuttingPartyController::class, 'cuttingPartyList'])->name('cutting-party-list')->middleware('permission:cutting-party-list');
Route::get('/cutting-party-save-page', [CuttingPartyController::class, 'cuttingPartySavePage'])->name('cutting-party-save-page')->middleware('permission:cutting-party-save-page');
Route::post('/create-cutting-party', [CuttingPartyController::class, 'createcuttingParty'])->name('create-cutting-party')->middleware('permission:create-cutting-party');
Route::post('/update-cutting-party', [CuttingPartyController::class, 'updatecuttingParty'])->name('update-cutting-party')->middleware('permission:update-cutting-party');
Route::get('/cutting-party-delete', [CuttingPartyController::class, 'cuttingPartyDelete'])->name('cutting-party-delete')->middleware('permission:cutting-party-delete');

// Cuttings
Route::get('/cutting-list', [CuttingController::class, 'cuttingList'])->name('cutting-list')->middleware('permission:cutting-list');
Route::get('/cutting-save-page', [CuttingController::class, 'cuttingSavePage'])->name('cutting-save-page')->middleware('permission:cutting-save-page');
Route::post('/create-cutting', [CuttingController::class, 'createCutting'])->name('create-cutting')->middleware('permission:create-cutting');
Route::get('/cutting-receive-list', [CuttingController::class, 'cuttingReceiveList'])->name('cutting-receive-list')->middleware('permission:cutting-receive-list');
Route::get('/cutting-receive-page', [CuttingController::class, 'cuttingReceivePage'])->name('cutting-receive-page')->middleware('permission:cutting-receive-page');
Route::post('/create-cutting-receive', [CuttingController::class, 'createCuttingReceive'])->name('create-cutting-receive')->middleware('permission:create-cutting-receive');
