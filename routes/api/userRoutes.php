<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserShowController;
use App\Http\Controllers\Api\User\UserIndexController;
use App\Http\Controllers\Api\User\UserStoreController;
use App\Http\Controllers\Api\User\UserUpdateController;
use App\Http\Controllers\Api\User\UserDestroyController;
use App\Http\Controllers\Api\User\UpdatePasswordController;

Route::get('users', UserIndexController::class)
    ->name('api.v1.users.index');

Route::post('users', UserStoreController::class)
    ->name('api.v1.users.store');

Route::get('users/{user}', UserShowController::class)
    ->name('api.v1.users.show');

Route::match(['put', 'patch'], 'users/{user}', UserUpdateController::class)
    ->name('api.v1.users.update');

Route::match(['put', 'patch'], 'users/{user}/password', UpdatePasswordController::class)
    ->name('api.v1.users.password.update');

Route::delete('users/{user}', UserDestroyController::class)
    ->name('api.v1.users.destroy');
