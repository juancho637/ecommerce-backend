<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Product\ProductShowController;
use App\Http\Controllers\Api\Product\ProductIndexController;
use App\Http\Controllers\Api\Product\ProductStoreController;
use App\Http\Controllers\Api\Product\ProductUpdateController;
use App\Http\Controllers\Api\Product\ProductDestroyController;
use App\Http\Controllers\Api\Product\ProductStockStepController;
use App\Http\Controllers\Api\Product\Type\ProductTypeIndexController;
use App\Http\Controllers\Api\Product\ProductSpecificationStepController;
use App\Http\Controllers\Api\Product\Resource\ProductResourceDestroyController;

Route::get('products/types', ProductTypeIndexController::class)
    ->name('api.v1.product_types.index');

Route::get('products', ProductIndexController::class)
    ->name('api.v1.products.index');

Route::post('products/general', ProductStoreController::class)
    ->name('api.v1.products_general.store');

Route::post('products/{product}/stocks_step', ProductStockStepController::class)
    ->name('api.v1.product_stocks.store');

Route::post('products/{product}/specifications_step', ProductSpecificationStepController::class)
    ->name('api.v1.product_specifications.store');

// Route::post('products/{product}/publish', ProductPublishController::class)
//     ->name('api.v1.products.publish');

Route::get('products/{product}', ProductShowController::class)
    ->name('api.v1.products.show');

Route::match(['put', 'patch'], 'products/{product}/general', ProductUpdateController::class)
    ->name('api.v1.products_general.update');

Route::delete('products/{product}', ProductDestroyController::class)
    ->name('api.v1.products.destroy');

Route::delete('products/{product}/images/{resource}', ProductResourceDestroyController::class)
    ->name('api.v1.products.images.destroy');
