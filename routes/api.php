<?php

use App\Http\Controllers\Api\DataPenjualanController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\MembershipUserController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentHistoryUserController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/user',UserController::class);
Route::resource('/register',RegisterController::class);
Route::resource('/login', LoginController::class);
Route::resource('/membership',MembershipController::class);
Route::middleware('auth:sanctum')->post('/payment', [PaymentController::class, 'store']);
Route::middleware('auth:sanctum')->get('/payment-history-user', [PaymentHistoryUserController::class, 'store']);
Route::resource('/data-penjualan', DataPenjualanController::class);
Route::resource('/membership-user', MembershipUserController::class);


