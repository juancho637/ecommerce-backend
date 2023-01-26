<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeShowController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeIndexController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeStoreController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeUpdateController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeDestroyController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeOption\ProductAttributeProductAttributeOptionIndexController;

Route::get('product_attributes', ProductAttributeIndexController::class)
    ->name('api.v1.product_attributes.index');

Route::post('product_attributes', ProductAttributeStoreController::class)
    ->name('api.v1.product_attributes.store');

Route::get('product_attributes/{productAttribute}', ProductAttributeShowController::class)
    ->name('api.v1.product_attributes.show');

Route::match(['put', 'patch'], 'product_attributes/{productAttribute}', ProductAttributeUpdateController::class)
    ->name('api.v1.product_attributes.update');

Route::delete('product_attributes/{productAttribute}', ProductAttributeDestroyController::class)
    ->name('api.v1.product_attributes.destroy');

Route::get(
    'product_attributes/{productAttribute}/product_attribute_options',
    ProductAttributeProductAttributeOptionIndexController::class
)->name('api.v1.product_attributes.product_attribute_options.index');
