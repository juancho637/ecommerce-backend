<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Category\CategoryShowController;
use App\Http\Controllers\Api\Category\CategoryIndexController;
use App\Http\Controllers\Api\Category\CategoryStoreController;
use App\Http\Controllers\Api\Category\CategoryUpdateController;
use App\Http\Controllers\Api\Category\CategoryDestroyController;

Route::get('categories', CategoryIndexController::class)
    ->name('api.v1.categories.index');

Route::post('categories', CategoryStoreController::class)
    ->name('api.v1.categories.store');

Route::get('categories/{category}', CategoryShowController::class)
    ->name('api.v1.categories.show');

Route::match(['put', 'patch'], 'categories/{category}', CategoryUpdateController::class)
    ->name('api.v1.categories.update');

Route::delete('categories/{category}', CategoryDestroyController::class)
    ->name('api.v1.categories.destroy');
