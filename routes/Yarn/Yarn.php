<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Yarn\YarnSaleController;
use App\Http\Controllers\Yarn\YarnPartyController;
use App\Http\Controllers\Yarn\YarnPurchaseController;




// Yarn Party
Route::get('/yarn-party-list', [YarnPartyController::class, 'yarnPartyList'])->name('yarn-party-list')->middleware('permission:yarn-party-list');
Route::get('/yarn-party-save-page', [YarnPartyController::class, 'yarnPartySavePage'])->name('yarn-party-save-page')->middleware('permission:yarn-party-save-page');
Route::post('/create-yarn-party', [YarnPartyController::class, 'createYarnParty'])->name('create-yarn-party')->middleware('permission:create-yarn-party');
Route::post('/update-yarn-party', [YarnPartyController::class, 'updateYarnParty'])->name('update-yarn-party')->middleware('permission:update-yarn-party');
Route::get('/yarn-party-delete', [YarnPartyController::class, 'yarnPartyDelete'])->name('yarn-party-delete')->middleware('permission:yarn-party-delete');

// Yarn Purchase
Route::get('/', [YarnPurchaseController::class, 'yarnPurchaseList'])->name('yarn-purchase-list')->middleware('permission:yarn-purchase-list');
Route::get('/yarn-purchase-list', [YarnPurchaseController::class, 'yarnPurchaseList'])->name('yarn-purchase-list')->middleware('permission:yarn-purchase-list');
Route::get('/yarn-purchase-save-page', [YarnPurchaseController::class, 'yarnPurchaseSavePage'])->name('yarn-purchase-save-page')->middleware('permission:yarn-purchase-save-page');
Route::post('/create-yarn-purchase', [YarnPurchaseController::class, 'createYarnPurchase'])->name('create-yarn-purchase')->middleware('permission:create-yarn-purchase');
Route::post('/update-yarn-purchase', [YarnPurchaseController::class, 'updateYarnPurchase'])->name('update-yarn-purchase')->middleware('permission:update-yarn-purchase');
Route::get('/yarn-purchase-delete', [YarnPurchaseController::class, 'yarnPurchaseDelete'])->name('yarn-purchase-delete')->middleware('permission:yarn-purchase-delete');

// Yarn Sale
Route::get('/yarn-sale-list', [YarnSaleController::class, 'yarnSaleList'])->name('yarn-sale-list')->middleware('permission:yarn-sale-list');
Route::get('/yarn-sale-page', [YarnSaleController::class, 'yarnSalePage'])->name('yarn-sale-page')->middleware('permission:yarn-sale-page');
Route::post('/create-yarn-sale', [YarnSaleController::class, 'createYarnSale'])->name('create-yarn-sale')->middleware('permission:create-yarn-sale');

// Yarn Payment
Route::post('/save-yarn-payment', [YarnPartyController::class, 'saveYarnPayment'])->name('save-yarn-payment')->middleware('permission:save-yarn-payment');
