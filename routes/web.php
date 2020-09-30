<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
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

Route::resource('/product', ProductController::class);
Route::post('/product/available', [ProductController::class, 'available']);

Route::resource('/lot', LotController::class);
Route::post('/lot/available', [LotController::class, 'available']);