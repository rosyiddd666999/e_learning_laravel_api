<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/user', [App\Http\Controllers\Users\GetUserController::class, 'getUser']);
