<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\City\CityController;
use App\Http\Controllers\Api\State\StateController;
use App\Http\Controllers\Api\Country\CountryController;

Route::group(['prefix' => 'v1'], function () {

    Route::apiResource('countries', CountryController::class, [
        'only' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'as' => 'api'
    ]);
    Route::apiResource('states', StateController::class, [
        'only' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'as' => 'api'
    ]);
    Route::apiResource('cities', CityController::class, [
        'only' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'as' => 'api'
    ]);
});
