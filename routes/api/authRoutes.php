<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthMeController;
use App\Http\Controllers\Api\Auth\AuthLoginController;
use App\Http\Controllers\Api\Auth\AuthProviderController;
use App\Http\Controllers\Api\Auth\AuthRegisterController;

Route::post('auth/login', AuthLoginController::class)
    ->name('api.v1.auth.login');

Route::get('auth/me', AuthMeController::class)
    ->name('api.v1.auth.me');

Route::post('auth/register', AuthRegisterController::class)
    ->name('api.v1.auth.register');

Route::post('auth/{provider}', AuthProviderController::class)
    ->name('api.v1.auth.provider');
