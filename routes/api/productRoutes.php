<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Product\ProductShowController;
use App\Http\Controllers\Api\Product\ProductIndexController;
use App\Http\Controllers\Api\Product\ProductStoreController;
use App\Http\Controllers\Api\Product\ProductUpdateController;
use App\Http\Controllers\Api\Product\ProductDestroyController;

Route::get('products', ProductIndexController::class)
    ->name('api.v1.products.index');

Route::post('products', ProductStoreController::class)
    ->name('api.v1.products.store');

Route::get('products/{product}', ProductShowController::class)
    ->name('api.v1.products.show');

Route::match(['put', 'patch'], 'products/{product}', ProductUpdateController::class)
    ->name('api.v1.products.update');

Route::delete('products/{product}', ProductDestroyController::class)
    ->name('api.v1.products.destroy');
