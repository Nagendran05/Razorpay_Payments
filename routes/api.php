<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

Route::post('/create-order',[PaymentController::class,'createorder']);
Route::post('/verify-payment',[PaymentController::class,'verifypayments']);

Route::get('/products',[ProductController::class,'index']);
Route::post('add-to-cart',[CartController::class,'add']);
Route::get('cart',[CartController::class,'list']);
