<?php

use App\Http\Controllers\Api\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/auth/google", [GoogleAuthController::class, "redirectToGoogle"]) -> name("redirect.google");
Route::get("/auth/google/callback", [GoogleAuthController::class, "handleGoogleCallback"]);
