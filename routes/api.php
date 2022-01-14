<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\City\CityController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\State\StateController;
use App\Http\Controllers\Api\Country\CountryController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/login', [AuthController::class, 'login'])
        ->name('api.v1.auth.login');
    Route::post('auth/register', [AuthController::class, 'register'])
        ->name('api.v1.auth.register');

    Route::apiResource('countries', CountryController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('states', StateController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('cities', CityController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('users', UserController::class, [
        'as' => 'api.v1'
    ]);
});
