<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/product', "product")->where("any", ".*");
Route::view('/category', "category")->where("any", ".*");

// Route::view('/product', "product_view")->where("any", ".*");
// Route::view('/category', "category_view")->where("any", ".*");




