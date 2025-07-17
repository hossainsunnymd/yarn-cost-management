<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fabric\FabricController;




// Fabric
Route::get('/fabric-list', [FabricController::class, 'fabricList'])->name('fabric-list')->middleware('permission:fabric-list');
Route::get('/fabric-sale-page', [FabricController::class, 'fabricSalePage'])->name('fabric-sale-page')->middleware('permission:fabric-sale-page');
Route::post('/fabric-sale', [FabricController::class, 'fabricSale'])->name('fabric-sale')->middleware('permission:fabric-sale');
Route::get('/fabric-sale-list', [FabricController::class, 'fabricSaleList'])->name('fabric-sale-list')->middleware('permission:fabric-sale-list');
