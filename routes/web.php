<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Dyeing\DyeingController;
use App\Http\Controllers\Fabric\FabricController;
use App\Http\Controllers\Sewing\SewingController;
use App\Http\Controllers\Yarn\YarnSaleController;
use App\Http\Controllers\Yarn\YarnPartyController;
use App\Http\Controllers\Cutting\CuttingController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Knitting\KnittingController;
use App\Http\Controllers\Yarn\YarnPurchaseController;
use App\Http\Controllers\Dyeing\DyeingPartyController;
use App\Http\Controllers\Sewing\SewingPartyController;
use App\Http\Controllers\Cutting\CuttingPartyController;
use App\Http\Controllers\Knitting\KnittingSaleController;
use App\Http\Controllers\Knitting\KnittingPartyController;

// Users
Route::post('/create-user', [UserController::class, 'createUser'])->name('create-user')->middleware('permission:create-user');
Route::post('/update-user', [UserController::class, 'updateUser'])->name('update-user')->middleware('permission:update-user');
Route::get('/delete-user', [UserController::class, 'deleteUser'])->name('delete-user')->middleware('permission:delete-user');
Route::get('/list-user', [UserController::class, 'listUser'])->name('list-user')->middleware('permission:list-user');
Route::get('/user-save-page', [UserController::class, 'userSavePage'])->name('user-save-page')->middleware('permission:user-save-page');

// Roles
Route::get('/list-role', [RoleController::class, 'roleList'])->name('list-role')->middleware('permission:list-role');
Route::get('/role-save-page', [RoleController::class, 'roleSavePage'])->name('role-save-page')->middleware('permission:role-save-page');
Route::post('/create-role', [RoleController::class, 'createRole'])->name('create-role')->middleware('permission:create-role');
Route::post('/update-role', [RoleController::class, 'updateRole'])->name('update-role')->middleware('permission:update-role');
Route::get('/delete-role', [RoleController::class, 'deleteRole'])->name('delete-role')->middleware('permission:delete-role');

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

// Dyeing Party
Route::get('/dyeing-party-list', [DyeingPartyController::class, 'dyeingPartyList'])->name('dyeing-party-list')->middleware('permission:dyeing-party-list');
Route::get('/dyeing-party-save-page', [DyeingPartyController::class, 'dyeingPartySavePage'])->name('dyeing-party-save-page')->middleware('permission:dyeing-party-save-page');
Route::post('/create-dyeing-party', [DyeingPartyController::class, 'createDyeingParty'])->name('create-dyeing-party')->middleware('permission:create-dyeing-party');
Route::post('/update-dyeing-party', [DyeingPartyController::class, 'updateDyeingParty'])->name('update-dyeing-party')->middleware('permission:update-dyeing-party');
Route::get('/dyeing-party-delete', [DyeingPartyController::class, 'dyeingPartyDelete'])->name('dyeing-party-delete')->middleware('permission:dyeing-party-delete');

// Dyeing
Route::get('/dyeing-list', [DyeingController::class, 'dyeingList'])->name('dyeing-list')->middleware('permission:dyeing-list');
Route::get('/dyeing-save-page', [DyeingController::class, 'dyeingSavePage'])->name('dyeing-save-page')->middleware('permission:dyeing-save-page');
Route::post('/create-dyeing', [DyeingController::class, 'createDyeing'])->name('create-dyeing')->middleware('permission:create-dyeing');
Route::get('/dyeing-receive-page', [DyeingController::class, 'dyeingReceivePage'])->name('dyeing-receive-page')->middleware('permission:dyeing-receive-page');
Route::post('/create-dyeing-receive', [DyeingController::class, 'createDyeingReceive'])->name('create-dyeing-receive')->middleware('permission:create-dyeing-receive');

// Dyeing Payment
Route::post('/save-dyeing-payment', [DyeingPartyController::class, 'saveDyeingPayment'])->name('save-dyeing-payment')->middleware('permission:save-dyeing-payment');

// Fabric
Route::get('/fabric-list', [FabricController::class, 'fabricList'])->name('fabric-list')->middleware('permission:fabric-list');
Route::get('/fabric-sale-page', [FabricController::class, 'fabricSalePage'])->name('fabric-sale-page')->middleware('permission:fabric-sale-page');
Route::post('/fabric-sale', [FabricController::class, 'fabricSale'])->name('fabric-sale')->middleware('permission:fabric-sale');
Route::get('/fabric-sale-list', [FabricController::class, 'fabricSaleList'])->name('fabric-sale-list')->middleware('permission:fabric-sale-list');

// Category
Route::get('/list-category', [CategoryController::class, 'listCategory'])->name('list-category')->middleware('permission:list-category');
Route::get('/category-save-page', [CategoryController::class, 'categorySavePage'])->name('category-save-page')->middleware('permission:category-save-page');
Route::post('/create-category', [CategoryController::class, 'createCategory'])->name('create-category')->middleware('permission:create-category');
Route::post('/update-category', [CategoryController::class, 'updateCategory'])->name('update-category')->middleware('permission:update-category');
Route::get('/delete-category', [CategoryController::class, 'deleteCategory'])->name('delete-category')->middleware('permission:delete-category');

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

// Products
Route::get('/product-list', [ProductController::class, 'productList'])->name('product-list')->middleware('permission:product-list');
Route::get('/product-update-page', [ProductController::class, 'updateProductPage'])->name('product-update-page')->middleware('permission:product-update-page');
Route::post('/update-product', [ProductController::class, 'updateProduct'])->name('update-product')->middleware('permission:update-product');

// Sale Page
Route::get('/sale-page', [InvoiceController::class, 'salePage'])->name('sale-page')->middleware('permission:sale-page');

// Customers
Route::get('/customer-list', [CustomerController::class, 'customerList'])->name('customer-list')->middleware('permission:customer-list');
Route::get('/customer-save-page', [CustomerController::class, 'customerSavePage'])->name('customer-save-page')->middleware('permission:customer-save-page');
Route::post('/create-customer', [CustomerController::class, 'createCustomer'])->name('create-customer')->middleware('permission:create-customer');
Route::post('/update-customer', [CustomerController::class, 'updateCustomer'])->name('update-customer')->middleware('permission:update-customer');
Route::get('/delete-customer', [CustomerController::class, 'deleteCustomer'])->name('delete-customer')->middleware('permission:delete-customer');

// Invoices
Route::get('/invoice-list', [InvoiceController::class, 'invoiceList'])->name('invoice-list')->middleware('permission:invoice-list');
Route::post('/create-invoice', [InvoiceController::class, 'createInvoice'])->name('create-invoice')->middleware('permission:create-invoice');
Route::get('/delete-invoice', [InvoiceController::class, 'deleteInvoice'])->name('delete-invoice')->middleware('permission:delete-invoice');
