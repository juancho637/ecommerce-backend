<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Country\CountryShowController;
use App\Http\Controllers\Api\Country\CountryIndexController;
use App\Http\Controllers\Api\Country\CountryStoreController;
use App\Http\Controllers\Api\Country\CountryUpdateController;
use App\Http\Controllers\Api\Country\CountryDestroyController;

Route::get('countries', CountryIndexController::class)
    ->name('api.v1.countries.index');

Route::post('countries', CountryStoreController::class)
    ->name('api.v1.countries.store');

Route::get('countries/{country}', CountryShowController::class)
    ->name('api.v1.countries.show');

Route::match(['put', 'patch'], 'countries/{country}', CountryUpdateController::class)
    ->name('api.v1.countries.update');

Route::delete('countries/{country}', CountryDestroyController::class)
    ->name('api.v1.countries.destroy');
