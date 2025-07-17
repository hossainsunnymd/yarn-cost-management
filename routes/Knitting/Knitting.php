<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Knitting\KnittingController;
use App\Http\Controllers\Knitting\KnittingSaleController;
use App\Http\Controllers\Knitting\KnittingPartyController;


// Knitting Party
Route::get('/knitting-party-list', [KnittingPartyController::class, 'knittingPartyList'])->name('knitting-party-list')->middleware('permission:knitting-party-list');
Route::get('/knitting-party-save-page', [KnittingPartyController::class, 'knittingPartySavePage'])->name('knitting-party-save-page')->middleware('permission:knitting-party-save-page');
Route::post('/create-knitting-party', [KnittingPartyController::class, 'createKnittingParty'])->name('create-knitting-party')->middleware('permission:create-knitting-party');
Route::post('/update-knitting-party', [KnittingPartyController::class, 'updateKnittingParty'])->name('update-knitting-party')->middleware('permission:update-knitting-party');
Route::get('/knitting-party-delete', [KnittingPartyController::class, 'knittingPartyDelete'])->name('knitting-party-delete')->middleware('permission:knitting-party-delete');

// Knitting
Route::get('/knitting-list', [KnittingController::class, 'knittingList'])->name('knitting-list')->middleware('permission:knitting-list');
Route::get('/knitting-save-page', [KnittingController::class, 'knittingSavePage'])->name('knitting-save-page')->middleware('permission:knitting-save-page');
Route::post('/create-knitting', [KnittingController::class, 'createKnitting'])->name('create-knitting')->middleware('permission:create-knitting');

// Knitting Receive
Route::get('/knitting-receive-page', [KnittingController::class, 'knittingReceivePage'])->name('knitting-receive-page')->middleware('permission:knitting-receive-page');
Route::post('/create-knitting-receive', [KnittingController::class, 'createKnittingReceive'])->name('create-knitting-receive')->middleware('permission:create-knitting-receive');
Route::get('/knitting-receive-list', [KnittingController::class, 'knittingReceiveList'])->name('knitting-receive-list')->middleware('permission:knitting-receive-list');

// Knitting Sale
Route::get('/knitting-sale-page', [KnittingSaleController::class, 'knittingSalePage'])->name('knitting-sale-page')->middleware('permission:knitting-sale-page');
Route::post('/create-knitting-sale', [KnittingSaleController::class, 'createKnittingSale'])->name('create-knitting-sale')->middleware('permission:create-knitting-sale');
Route::get('/knitting-sale-list', [KnittingSaleController::class, 'knittingSaleList'])->name('knitting-sale-list')->middleware('permission:knitting-sale-list');

// Knitting Payment
Route::post('/save-knitting-payment', [KnittingPartyController::class, 'saveKnittingPayment'])->name('save-knitting-payment')->middleware('permission:save-knitting-payment');
