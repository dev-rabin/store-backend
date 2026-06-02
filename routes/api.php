<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/categories', [ProductController::class, 'categories']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::put('/cart/{id}', [CartController::class, 'updateCart']);
    Route::delete('/cart/{id}', [CartController::class, 'removeCart']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
    Route::get('/orders', [CheckoutController::class, 'getOrders']);
    Route::get('/orders/{id}', [CheckoutController::class, 'getOrder']);
});


Route::middleware('auth:sanctum')->post(
    '/payment/create',
    [PaymentController::class, 'create']
);

Route::post(
    '/payment/webhook',
    [PaymentController::class, 'webhook']
);