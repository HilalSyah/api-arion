<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DataPenjualanController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\MembershipHistoryUserController;
use App\Http\Controllers\Api\MembershipUserController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentHistoryUserController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ScanBarcodeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/user', UserController::class);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/user-profile', UserProfile::class);
    Route::put('/user-profile', [UserProfile::class, 'update']);
});

Route::resource('/register',RegisterController::class);
Route::resource('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/membership',MembershipController::class);
});

Route::middleware('auth:sanctum')->post('/payment', [PaymentController::class, 'store']);
Route::middleware('auth:sanctum')->get('/payment-history-user', [PaymentHistoryUserController::class, 'store']);
Route::resource('/data-penjualan', DataPenjualanController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/membership-user', MembershipUserController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/membership-user-history', MembershipHistoryUserController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/scan-barcode', ScanBarcodeController::class)->only(['store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index']);
});

Route::get('/leaderboard', [LeaderboardController::class, 'index']);



