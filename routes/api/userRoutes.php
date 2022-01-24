<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;

Route::apiResource('users', UserController::class, [
    'as' => 'api.v1'
]);
