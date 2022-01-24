<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::post('auth/login', [AuthController::class, 'login'])
    ->name('api.v1.auth.login');

Route::post('auth/register', [AuthController::class, 'register'])
    ->name('api.v1.auth.register');

Route::post('auth/{provider}', [AuthController::class, 'provider']);
