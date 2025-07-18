<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dyeing\DyeingController;
use App\Http\Controllers\Dyeing\DyeingPartyController;

// Dyeing Party
Route::get('/dyeing-party-list', [DyeingPartyController::class, 'dyeingPartyList'])->name('dyeing-party-list')->middleware('permission:dyeing-party-list');
Route::get('/dyeing-party-save-page', [DyeingPartyController::class, 'dyeingPartySavePage'])->name('dyeing-party-save-page')->middleware('permission:dyeing-party-save-page');
Route::post('/create-dyeing-party', [DyeingPartyController::class, 'createDyeingParty'])->name('create-dyeing-party')->middleware('permission:create-dyeing-party');
Route::post('/update-dyeing-party', [DyeingPartyController::class, 'updateDyeingParty'])->name('update-dyeing-party')->middleware('permission:update-dyeing-party');
Route::get('/dyeing-party-delete', [DyeingPartyController::class, 'dyeingPartyDelete'])->name('dyeing-party-delete')->middleware('permission:dyeing-party-delete');
Route::get('/dyeing-party-detail-list', [DyeingPartyController::class, 'dyeingPartyDetailList'])->name('dyeing-party-details');

// Dyeing
Route::get('/dyeing-list', [DyeingController::class, 'dyeingList'])->name('dyeing-list')->middleware('permission:dyeing-list');
Route::get('/dyeing-save-page', [DyeingController::class, 'dyeingSavePage'])->name('dyeing-save-page')->middleware('permission:dyeing-save-page');
Route::post('/create-dyeing', [DyeingController::class, 'createDyeing'])->name('create-dyeing')->middleware('permission:create-dyeing');
Route::get('/dyeing-receive-page', [DyeingController::class, 'dyeingReceivePage'])->name('dyeing-receive-page')->middleware('permission:dyeing-receive-page');
Route::post('/create-dyeing-receive', [DyeingController::class, 'createDyeingReceive'])->name('create-dyeing-receive')->middleware('permission:create-dyeing-receive');

// Dyeing Payment
Route::post('/save-dyeing-payment', [DyeingPartyController::class, 'saveDyeingPayment'])->name('save-dyeing-payment')->middleware('permission:save-dyeing-payment');
Route::get('/dyeing-payment-list', [DyeingPartyController::class, 'dyeingPaymentList'])->name('dyeing-payment-list');
