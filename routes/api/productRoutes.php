<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Product\ProductShowController;
use App\Http\Controllers\Api\Product\ProductIndexController;
use App\Http\Controllers\Api\Product\ProductStoreController;
use App\Http\Controllers\Api\Product\ProductFinishController;
use App\Http\Controllers\Api\Product\ProductUpdateController;
use App\Http\Controllers\Api\Product\ProductDestroyController;
use App\Http\Controllers\Api\Product\Type\ProductTypeIndexController;
use App\Http\Controllers\Api\Product\Resource\ProductResourceDestroyController;
use App\Http\Controllers\Api\Product\ProductStock\ProductProductStockIndexController;
use App\Http\Controllers\Api\Product\ProductStock\ProductProductStockStoreController;

Route::get('products/types', ProductTypeIndexController::class)
    ->name('api.v1.product_types.index');

Route::get('products', ProductIndexController::class)
    ->name('api.v1.products.index');

Route::post('products', ProductStoreController::class)
    ->name('api.v1.products.store');

Route::post('products/{product}/finish', ProductFinishController::class)
    ->name('api.v1.products.finish');

// Route::post('products/{product}/publish', ProductPublishController::class)
//     ->name('api.v1.products.publish');

Route::get('products/{product}', ProductShowController::class)
    ->name('api.v1.products.show');

Route::match(['put', 'patch'], 'products/{product}', ProductUpdateController::class)
    ->name('api.v1.products.update');

Route::delete('products/{product}', ProductDestroyController::class)
    ->name('api.v1.products.destroy');

Route::delete('products/{product}/images/{resource}', ProductResourceDestroyController::class)
    ->name('api.v1.products.images.destroy');

Route::get('products/{product}/product_stocks', ProductProductStockIndexController::class)
    ->name('api.v1.products.product_stocks.index');

Route::post('products/{product}/product_stocks', ProductProductStockStoreController::class)
    ->name('api.v1.products.product_stocks.store');

Route::match(
    ['put', 'patch'],
    'products/{product}/product_stocks',
    ProductProductStockStoreController::class
)->name('api.v1.products.product_stocks.store');
