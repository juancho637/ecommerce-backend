<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\State\StateShowController;
use App\Http\Controllers\Api\State\StateIndexController;
use App\Http\Controllers\Api\State\StateStoreController;
use App\Http\Controllers\Api\State\StateUpdateController;
use App\Http\Controllers\Api\State\StateDestroyController;

Route::get('states', StateIndexController::class)
    ->name('api.v1.states.index');

Route::post('states', StateStoreController::class)
    ->name('api.v1.states.store');

Route::get('states/{state}', StateShowController::class)
    ->name('api.v1.states.show');

Route::match(['put', 'patch'], 'states/{state}', StateUpdateController::class)
    ->name('api.v1.states.update');

Route::delete('states/{state}', StateDestroyController::class)
    ->name('api.v1.states.destroy');
