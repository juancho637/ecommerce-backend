<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\City\CityController;
use App\Http\Controllers\Api\State\StateController;
use App\Http\Controllers\Api\Country\CountryController;

Route::group(['prefix' => 'v1'], function () {

    Route::apiResource('countries', CountryController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('states', StateController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('cities', CityController::class, [
        'as' => 'api.v1'
    ]);
});
