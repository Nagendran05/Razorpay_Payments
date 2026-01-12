<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/create-order',[PaymentController::class,'createorder']);
Route::post('/verify-payments',[PaymentController::class,'verifypayments']);
Route::post('/razorpay-webhook',[PaymentController::class,'webhook']);
