<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route xử lý truy cập trực tiếp vào các tệp CSS
// Route::get('/stylesheet/{filename}', function ($filename) {
//     return response()->file(public_path('stylesheet/' . $filename));
// });

// Route Vue.js cho các route khác
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
