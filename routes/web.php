<?php

use App\Http\Controllers\DyeingController;
use App\Http\Controllers\FabricController;
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


//yarn sale
Route::get('/yarn-sale-list',[YarnController::class,'yarnSaleList'])->name('yarn-sale-list');
Route::get('/yarn-sale-page',[YarnController::class,'yarnSalePage'])->name('yarn-sale-page');
Route::post('/create-yarn-sale',[YarnController::class,'createYarnSale'])->name('create-yarn-sale');



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

//knitting receive
Route::get('/knitting-receive-page',[KnittingController::class,'knittingReceivePage'])->name('knitting-receive-page');
Route::post('/create-knitting-receive',[KnittingController::class,'createKnittingReceive'])->name('create-knitting-receive');
Route::get('/knitting-receive-list',[KnittingController::class,'knittingReceiveList'])->name('knitting-receive-list');

//knitting sale
Route::get('/knitting-sale-page',[KnittingController::class,'knittingSalePage'])->name('knitting-sale-page');
Route::post('/create-knitting-sale',[KnittingController::class,'createKnittingSale'])->name('create-knitting-sale');
Route::get('/knitting-sale-list',[KnittingController::class,'knittingSaleList'])->name('knitting-sale-list');



//dyeing party
Route::get('/dyeing-party-list',[DyeingController::class,'dyeingPartyList'])->name('dyeing-party-list');
Route::get('/dyeing-party-save-page',[DyeingController::class,'dyeingPartySavePage'])->name('dyeing-party-save-page');
Route::post('/create-dyeing-party',[DyeingController::class,'createDyeingParty'])->name('create-dyeing-party');
Route::post('/update-dyeing-party',[DyeingController::class,'updateDyeingParty'])->name('update-dyeing-party');
Route::get('/dyeing-party-delete',[DyeingController::class,'dyeingPartyDelete'])->name('dyeing-party-delete');



//dyeing
Route::get('/dyeing-list',[DyeingController::class,'dyeingList'])->name('dyeing-list');
Route::get('/dyeing-save-page',[DyeingController::class,'dyeingSavePage'])->name('dyeing-save-page');
Route::post('/create-dyeing',[DyeingController::class,'createDyeing'])->name('create-dyeing');
Route::get('/dyeing-receive-page',[DyeingController::class,'dyeingReceivePage'])->name('dyeing-receive-page');
Route::post('/create-dyeing-receive',[DyeingController::class,'createDyeingReceive'])->name('create-dyeing-receive');


//fabric
Route::get('/fabric-list',[FabricController::class,'fabricList'])->name('fabric-list');
Route::get('/fabric-sale-page',[FabricController::class,'fabricSalePage'])->name('fabric-sale-page');
Route::post('/create-fabric-sale',[FabricController::class,'createFabricSale'])->name('create-fabric-sale');
Route::get('/fabric-sale-list',[FabricController::class,'fabricSaleList'])->name('fabric-sale-list');
