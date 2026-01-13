<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/create-order',[PaymentController::class,'createorder']);
Route::post('/verify-payments',[PaymentController::class,'verifypayments']);
Route::post('/razorpay-webhook',[PaymentController::class,'webhook']);

Route::post('add-to-cart',[CartController::class,'add']);
Route::get('cart',[CartController::class,'list']);
