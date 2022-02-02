<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionShowController;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionIndexController;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionStoreController;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionUpdateController;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionDestroyController;

Route::get('product_attribute_options', ProductAttributeOptionIndexController::class)
    ->name('api.v1.product_attribute_options.index');

Route::post('product_attribute_options', ProductAttributeOptionStoreController::class)
    ->name('api.v1.product_attribute_options.store');

Route::get('product_attribute_options/{productAttributeOption}', ProductAttributeOptionShowController::class)
    ->name('api.v1.product_attribute_options.show');

Route::match(['put', 'patch'], 'product_attribute_options/{productAttributeOption}', ProductAttributeOptionUpdateController::class)
    ->name('api.v1.product_attribute_options.update');

Route::delete('product_attribute_options/{productAttributeOption}', ProductAttributeOptionDestroyController::class)
    ->name('api.v1.product_attribute_options.destroy');
