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

// Route::view('/product', "product")->where("any", ".*");
// Route::view('/category', "category")->where("any", ".*");

Route::get('/', function () {
    return view('welcome');
});

// Route để xử lý các yêu cầu Vue.js cho các route khác
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');




