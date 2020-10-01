<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
/Autenticacion de usuarios
*/
Auth::routes();


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/administrador', [AdminController::class, 'index'])->name('admin');
Route::get('/administrador/nuevo-producto', [AdminController::class, 'newProduct'])->name('newProduct');
Route::get('/proveedor', [ProviderController::class, 'index'])->name('provider');
Route::get('/cliente', [CustomerController::class, 'index'])->name('customer');

Route::resource('/product', ProductController::class);
Route::post('/product/available', [ProductController::class, 'available']);

Route::resource('/lot', LotController::class);
Route::post('/lot/available', [LotController::class, 'available']);
Route::post('/lot/sale', [LotController::class, 'lotSale']);

Route::resource('/sale', SaleController::class);
Route::post('/sale/available', [SaleController::class, 'available']);
Route::post('/sale/availableQuantity', [SaleController::class, 'availableQuantity']);
Route::post('/sale/sale', [SaleController::class, 'saleDetail']);
Route::post('/sale/inventory', [SaleController::class, 'viewInventory']);
Route::post('/saleEdit', [SaleController::class, 'saleEdit']);
Route::post('/sale/cancel', [SaleController::class, 'cancel']);
Route::post('/sale/invoice', [SaleController::class, 'invoice']);
Route::get('/sale/pdfInvoice/{id}', [SaleController::class, 'pdfInvoice']);
Route::post('/saleDelete', [SaleController::class, 'saleDelete']);