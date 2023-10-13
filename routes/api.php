<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Categories\CateController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
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



Route::post('/register', [RegisterController::class, "Register"]);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/{id}', [AuthController::class, "getProfile"]);
        Route::post('/{id}', [AuthController::class, "updateProfile"]);
    });
    Route::prefix("/category")->group(function () {
        Route::post("", [CateController::class, 'addCate']);
        Route::get("", [CateController::class, 'getCate']);
    });
    Route::prefix("/product")->group(function () {
        Route::post("", [ProductController::class, 'addProduct']);
        Route::get("", [ProductController::class, 'getProduct']);
        Route::get("/{id}", [ProductController::class, 'getDetailProduct']);
        Route::delete("/{id}", [ProductController::class, 'deleteProduct']);
        Route::put("/{id}", [ProductController::class, 'updateProduct']);
    });
    Route::get('/search', [ProductController::class, 'searchProduct']);
    Route::get('/byCate', [ProductController::class, 'byCategory']);
    Route::prefix("/addToCart")->group(function () {
        Route::post("", [CartController::class, 'addToCart']);
        Route::get("", [CartController::class, 'showCart']);
        Route::put("/{id}", [CartController::class, 'updateCartItem']);
        Route::delete("/{id}", [CartController::class, 'removeCart']);
        Route::delete("", [CartController::class, 'removeCarts']);
    });
    Route::prefix("/order")->group(function () {
        Route::post('', [OrderController::class, 'createOrder']);
        Route::get("",[OrderController::class,'getOrder']);
        Route::get('/{id}', [OrderController::class, 'getOrderWithDetails']);
        Route::get("/user/{id}",[OrderController::class,'getOrder']);
    });
});
