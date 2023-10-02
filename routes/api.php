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
Route::get('/product', [ProductController::class, 'product'])->name('product');
Route::post('/product/add', [ProductController::class, 'addproduct'])->name('addproduct');
Route::patch('/product/change', [ProductController::class, 'changeproduct'])->name('changeproduct');
Route::delete('/product/delete', [ProductController::class, 'deleteproduct'])->name('deleteproduct');

Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::post('/category/add', [CategoryController::class, 'addcategory'])->name('addcategory');
Route::patch('/category/change', [CategoryController::class, 'changecategory'])->name('changecategory');
Route::delete('/category/delete', [CategoryController::class, 'deletecategory'])->name('deletecategory');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
