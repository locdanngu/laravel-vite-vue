<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/product', [DemoController::class, 'product'])->name('product');
Route::post('/product/add', [DemoController::class, 'addproduct'])->name('addproduct');
Route::patch('/product/change', [DemoController::class, 'changeproduct'])->name('changeproduct');
Route::delete('/product/delete', [DemoController::class, 'deleteproduct'])->name('deleteproduct');

Route::get('/category', [DemoController::class, 'category'])->name('category');
Route::delete('/category/delete', [DemoController::class, 'deletecategory'])->name('deletecategory');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
