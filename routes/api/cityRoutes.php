<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\City\CityController;

Route::apiResource('cities', CityController::class, [
    'as' => 'api.v1'
]);
