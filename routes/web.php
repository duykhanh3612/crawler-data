<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TikiController;
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

Route::get('/', function () {
    return view('welcome');
    
});
Route::get('/show-products-info', [TikiController::class, 'showProductInfo']);
Route::get('/lay-id-san-pham', [TikiController::class, 'fetchProductIds']);
Route::get('/lay-san-pham', [TikiController::class, 'updateAllProductInfo']);
Route::get('/details', [TikiController::class, 'updatedetails']);
