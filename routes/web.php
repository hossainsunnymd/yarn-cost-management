<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YarnController;
use App\Http\Controllers\DyeingController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\SewingController;
use App\Http\Controllers\CuttingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KnittingController;
use App\Http\Controllers\SewingPartyController;
use App\Http\Controllers\CuttingPartyController;




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::middleware(['auth', 'verified'])->group(function () {



    Route::get('/dashboard', [YarnController::class, 'yarnPurchaseList'])->name('dashboard');
    //yarn party
    Route::get('/yarn-party-list', [YarnController::class, 'yarnPartyList'])->name('yarn-party-list');
    Route::get('/yarn-party-save-page', [YarnController::class, 'yarnPartySavePage'])->name('yarn-party-save-page');
    Route::post('/create-yarn-party', [YarnController::class, 'createYarnParty'])->name('create-yarn-party');
    Route::post('/update-yarn-party', [YarnController::class, 'updateYarnParty'])->name('update-yarn-party');
    Route::get('/yarn-party-delete', [YarnController::class, 'yarnPartyDelete'])->name('yarn-party-delete');


    //yarn purchase
    Route::get('/yarn-purchase-list', [YarnController::class, 'yarnPurchaseList'])->name('yarn-purchase-list');
    Route::get('/yarn-purchase-save-page', [YarnController::class, 'yarnPurchaseSavePage'])->name('yarn-purchase-save-page');
    Route::post('/create-yarn-purchase', [YarnController::class, 'createYarnPurchase'])->name('create-yarn-purchase');
    Route::post('/update-yarn-purchase', [YarnController::class, 'updateYarnPurchase'])->name('upadate-yarn-purchase');
    Route::get('/yarn-purchase-delete', [YarnController::class, 'yarnPurchaseDelete'])->name('yarn-purchase-delete');


    //yarn sale
    Route::get('/yarn-sale-list', [YarnController::class, 'yarnSaleList'])->name('yarn-sale-list');
    Route::get('/yarn-sale-page', [YarnController::class, 'yarnSalePage'])->name('yarn-sale-page');
    Route::post('/create-yarn-sale', [YarnController::class, 'createYarnSale'])->name('create-yarn-sale');

    //yarn payment
    Route::post('/save-yarn-payment', [YarnController::class, 'saveYarnPayment'])->name('save-yarn-payment');



    //knitting party
    Route::get('/knitting-party-list', [KnittingController::class, 'knittingPartyList'])->name('knitting-party-list');
    Route::get('/knitting-party-save-page', [KnittingController::class, 'knittingPartySavePage'])->name('knitting-party-save-page');
    Route::post('/create-knitting-party', [KnittingController::class, 'createKnittingParty'])->name('create-knitting-party');
    Route::post('/update-knitting-party', [KnittingController::class, 'updateKnittingParty'])->name('update-knitting-party');
    Route::get('/knitting-party-delete', [KnittingController::class, 'knittingPartyDelete'])->name('knitting-party-delete');



    //knitting
    Route::get('/knitting-list', [KnittingController::class, 'knittingList'])->name('knitting-list');
    Route::get('/knitting-save-page', [KnittingController::class, 'knittingSavePage'])->name('knitting-save-page');
    Route::post('/create-knitting', [KnittingController::class, 'createKnitting'])->name('create-knitting');

    //knitting receive
    Route::get('/knitting-receive-page', [KnittingController::class, 'knittingReceivePage'])->name('knitting-receive-page');
    Route::post('/create-knitting-receive', [KnittingController::class, 'createKnittingReceive'])->name('create-knitting-receive');
    Route::get('/knitting-receive-list', [KnittingController::class, 'knittingReceiveList'])->name('knitting-receive-list');

    //knitting sale
    Route::get('/knitting-sale-page', [KnittingController::class, 'knittingSalePage'])->name('knitting-sale-page');
    Route::post('/create-knitting-sale', [KnittingController::class, 'createKnittingSale'])->name('create-knitting-sale');
    Route::get('/knitting-sale-list', [KnittingController::class, 'knittingSaleList'])->name('knitting-sale-list');

    //knitting payment
    Route::post('/save-knitting-payment', [KnittingController::class, 'saveKnittingPayment'])->name('save-knitting-payment');



    //dyeing party
    Route::get('/dyeing-party-list', [DyeingController::class, 'dyeingPartyList'])->name('dyeing-party-list');
    Route::get('/dyeing-party-save-page', [DyeingController::class, 'dyeingPartySavePage'])->name('dyeing-party-save-page');
    Route::post('/create-dyeing-party', [DyeingController::class, 'createDyeingParty'])->name('create-dyeing-party');
    Route::post('/update-dyeing-party', [DyeingController::class, 'updateDyeingParty'])->name('update-dyeing-party');
    Route::get('/dyeing-party-delete', [DyeingController::class, 'dyeingPartyDelete'])->name('dyeing-party-delete');



    //dyeing
    Route::get('/dyeing-list', [DyeingController::class, 'dyeingList'])->name('dyeing-list');
    Route::get('/dyeing-save-page', [DyeingController::class, 'dyeingSavePage'])->name('dyeing-save-page');
    Route::post('/create-dyeing', [DyeingController::class, 'createDyeing'])->name('create-dyeing');
    Route::get('/dyeing-receive-page', [DyeingController::class, 'dyeingReceivePage'])->name('dyeing-receive-page');
    Route::post('/create-dyeing-receive', [DyeingController::class, 'createDyeingReceive'])->name('create-dyeing-receive');

    //dyeing payment
    Route::post('/save-dyeing-payment', [DyeingController::class, 'saveDyeingPayment'])->name('save-dyeing-payment');


    //fabric
    Route::get('/fabric-list', [FabricController::class, 'fabricList'])->name('fabric-list');
    Route::get('/fabric-sale-page', [FabricController::class, 'fabricSalePage'])->name('fabric-sale-page');



    //category
    Route::get('/list-category', [CategoryController::class, 'listCategory'])->name('category.list');
    Route::get('/category-save-page', [CategoryController::class, 'categorySavePage'])->name('category.create');
    Route::post('/create-category', [CategoryController::class, 'createCategory'])->name('category.create');
    Route::post('/update-category', [CategoryController::class, 'updateCategory'])->name('category.update');
    Route::get('/delete-category', [CategoryController::class, 'deleteCategory'])->name('category.delete');


    //cuttings
    Route::get('/cutting-list', [CuttingController::class, 'cuttingList'])->name('cuttings.list');
    Route::get('/cutting-save-page', [CuttingController::class, 'cuttingSavePage'])->name('cuttings.save.page');
    Route::post('/create-cutting', [CuttingController::class, 'createCutting'])->name('cuttings.create');
    Route::get('/cutting-receive-list', [CuttingController::class, 'cuttingReceiveList'])->name('cuttings.receive.list');
    Route::get('/cutting-receive-page', [CuttingController::class, 'cuttingReceivePage'])->name('cuttings.receive.page');
    Route::post('/create-cutting-receive', [CuttingController::class, 'createCuttingReceive'])->name('cuttings.receive.create');


    //sewing party
    Route::get('/sewing-party-list', [SewingPartyController::class, 'sewingPartyList'])->name('sewing-party-list');
    Route::get('/sewing-party-save-page', [SewingPartyController::class, 'sewingPartySavePage'])->name('sewing-party-save-page');
    Route::post('/create-sewing-party', [SewingPartyController::class, 'createSewingParty'])->name('create-sewing-party');
    Route::post('/update-sewing-party', [SewingPartyController::class, 'updateSewingParty'])->name('update-sewing-party');
    Route::get('/sewing-party-delete', [SewingPartyController::class, 'sewingPartyDelete'])->name('sewing-party-delete');

    //sewing
    Route::get('/sewing-list', [SewingController::class, 'sewingList'])->name('sewing.list');
    Route::get('/sewing-save-page', [SewingController::class, 'sewingSavePage'])->name('sewing.save.page');
    Route::post('/create-sewing', [SewingController::class, 'createSewing'])->name('sewing.create');
    Route::get('/sewing-receive-list', [SewingController::class, 'sewingReceiveList'])->name('sewing.receive.list');
    Route::get('/sewing-receive-page', [SewingController::class, 'sewingReceivePage'])->name('sewing.receive.page');
    Route::post('/create-sewing-receive', [SewingController::class, 'createSewingReceive'])->name('sewing.receive.create');

    //sewing payment
    Route::post('/save-sewing-payment', [SewingPartyController::class, 'saveSewingPayment'])->name('save-sewing-payment');

    //products
    Route::get('/product-list', [ProductController::class, 'productList'])->name('product.list');

    //sale page
    Route::get('/sale-page', [InvoiceController::class, 'salePage'])->name('sale.page');


    //customer
    Route::get('/customer-list', [CustomerController::class, 'customerList'])->name('customer.list');
    Route::get('/customer-save-page', [CustomerController::class, 'customerSavePage'])->name('customer.save.page');
    Route::post('/create-customer', [CustomerController::class, 'createCustomer'])->name('customer.create');
    Route::post('/update-customer', [CustomerController::class, 'updateCustomer'])->name('customer.update');
    Route::get('/delete-customer', [CustomerController::class, 'deleteCustomer'])->name('customer.delete');

    //invoices
    Route::get('/invoice-list', [InvoiceController::class, 'invoiceList'])->name('invoice.list');
    Route::post('/create-invoice', [InvoiceController::class, 'createInvoice'])->name('invoice.create');


});
