<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Country\CountryController;

Route::apiResource('countries', CountryController::class, [
    'as' => 'api.v1'
]);
