<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

//login page
Route::get('/', [AuthController::class, 'loginPage'])->name('login-page');

//user login
Route::post('/login', [AuthController::class, 'login'])->name('login');

//user logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

require_once __DIR__.'/Category/Category.php';
require_once __DIR__.'/Customer/Customer.php';
require_once __DIR__.'/Cutting/Cutting.php';
require_once __DIR__.'/Dyeing/Dyeing.php';
require_once __DIR__.'/Fabric/Fabric.php';
require_once __DIR__.'/Invoice/Invoice.php';
require_once __DIR__.'/Knitting/Knitting.php';
require_once __DIR__.'/Product/Product.php';
require_once __DIR__.'/Role/Role.php';
require_once __DIR__.'/Sewing/Sewing.php';
require_once __DIR__.'/User/User.php';
require_once __DIR__.'/Yarn/Yarn.php';











