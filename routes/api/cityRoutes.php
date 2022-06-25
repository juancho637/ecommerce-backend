<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\City\CityShowController;
use App\Http\Controllers\Api\City\CityIndexController;
use App\Http\Controllers\Api\City\CityStoreController;
use App\Http\Controllers\Api\City\CityUpdateController;
use App\Http\Controllers\Api\City\CityDestroyController;

Route::get('cities', CityIndexController::class)
    ->name('api.v1.cities.index');

Route::post('cities', CityStoreController::class)
    ->name('api.v1.cities.store');

Route::get('cities/{city}', CityShowController::class)
    ->name('api.v1.cities.show');

Route::match(['put', 'patch'], 'cities/{city}', CityUpdateController::class)
    ->name('api.v1.cities.update');

Route::delete('cities/{city}', CityDestroyController::class)
    ->name('api.v1.cities.destroy');
