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

// Route::view('/{any}', "app")->where("any", ".*");


Route::get('/demo', [DemoController::class , 'index']);

Route::get('/API', [UserController::class, 'testapi'])->name('testapi');
Route::get('/API/delete', [UserController::class, 'deletetestapi'])->name('deletetestapi');
