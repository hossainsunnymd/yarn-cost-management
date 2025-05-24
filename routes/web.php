<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YarnController;
use App\Http\Controllers\KnittingController;



Route::get('/',[UserController::class,'index'])->name('welcome');

//yarn party
Route::get('/yarn-party-list',[YarnController::class,'yarnPartyList'])->name('yarn-party-list');
Route::get('/yarn-party-save-page',[YarnController::class,'yarnPartySavePage'])->name('yarn-party-save-page');
Route::post('/create-yarn-party',[YarnController::class,'createYarnParty'])->name('create-yarn-party');
Route::post('/update-yarn-party',[YarnController::class,'updateYarnParty'])->name('update-yarn-party');
Route::get('/yarn-party-delete',[YarnController::class,'yarnPartyDelete'])->name('yarn-party-delete');


//yarn purchase
Route::get('/yarn-purchase-list',[YarnController::class,'yarnPurchaseList'])->name('yarn-purchase-list');
Route::get('/yarn-purchase-save-page',[YarnController::class,'yarnPurchaseSavePage'])->name('yarn-purchase-save-page');
Route::post('/create-yarn-purchase',[YarnController::class,'createYarnPurchase'])->name('create-yarn-purchase');
Route::post('/update-yarn-purchase',[YarnController::class,'updateYarnPurchase'])->name('upadate-yarn-purchase');
Route::get('/yarn-purchase-delete',[YarnController::class,'yarnPurchaseDelete'])->name('yarn-purchase-delete');



//knitting party
Route::get('/knitting-party-list',[KnittingController::class,'knittingPartyList'])->name('knitting-party-list');
Route::get('/knitting-party-save-page',[KnittingController::class,'knittingPartySavePage'])->name('knitting-party-save-page');
Route::post('/create-knitting-party',[KnittingController::class,'createKnittingParty'])->name('create-knitting-party');
Route::post('/update-knitting-party',[KnittingController::class,'updateKnittingParty'])->name('update-knitting-party');
Route::get('/knitting-party-delete',[KnittingController::class,'knittingPartyDelete'])->name('knitting-party-delete');



//knitting
Route::get('/knitting-list',[KnittingController::class,'knittingList'])->name('knitting-list');
Route::get('/knitting-save-page',[KnittingController::class,'knittingSavePage'])->name('knitting-save-page');
Route::post('/create-knitting',[KnittingController::class,'createKnitting'])->name('create-knitting');
