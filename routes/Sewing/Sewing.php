<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sewing\SewingController;
use App\Http\Controllers\Sewing\SewingPartyController;



// Sewing Party
Route::get('/sewing-party-list', [SewingPartyController::class, 'sewingPartyList'])->name('sewing-party-list')->middleware('permission:sewing-party-list');
Route::get('/sewing-party-save-page', [SewingPartyController::class, 'sewingPartySavePage'])->name('sewing-party-save-page')->middleware('permission:sewing-party-save-page');
Route::post('/create-sewing-party', [SewingPartyController::class, 'createSewingParty'])->name('create-sewing-party')->middleware('permission:create-sewing-party');
Route::post('/update-sewing-party', [SewingPartyController::class, 'updateSewingParty'])->name('update-sewing-party')->middleware('permission:update-sewing-party');
Route::get('/sewing-party-delete', [SewingPartyController::class, 'sewingPartyDelete'])->name('sewing-party-delete')->middleware('permission:sewing-party-delete');

// Sewing
Route::get('/sewing-list', [SewingController::class, 'sewingList'])->name('sewing-list')->middleware('permission:sewing-list');
Route::get('/sewing-save-page', [SewingController::class, 'sewingSavePage'])->name('sewing-save-page')->middleware('permission:sewing-save-page');
Route::post('/create-sewing', [SewingController::class, 'createSewing'])->name('create-sewing')->middleware('permission:create-sewing');
Route::get('/sewing-receive-list', [SewingController::class, 'sewingReceiveList'])->name('sewing-receive-list')->middleware('permission:sewing-receive-list');
Route::get('/sewing-receive-page', [SewingController::class, 'sewingReceivePage'])->name('sewing-receive-page')->middleware('permission:sewing-receive-page');
Route::post('/create-sewing-receive', [SewingController::class, 'createSewingReceive'])->name('create-sewing-receive')->middleware('permission:create-sewing-receive');

// Sewing Payment
Route::post('/save-sewing-payment', [SewingPartyController::class, 'saveSewingPayment'])->name('save-sewing-payment')->middleware('permission:save-sewing-payment');
Route::get('/sewing-payment-list', [SewingPartyController::class, 'sewingPaymentList'])->name('sewing-payment-list');
