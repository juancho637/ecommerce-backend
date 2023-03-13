<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationShowController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationUpdateController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationDestroyController;
use App\Http\Controllers\Api\Product\ProductSpecification\ProductProductSpecificationIndexController;
use App\Http\Controllers\Api\Product\ProductSpecification\ProductProductSpecificationStoreController;

Route::get('product_specifications/{productSpecification}', ProductSpecificationShowController::class)
    ->name('api.v1.product_specifications.show');

Route::match(['put', 'patch'], 'product_specifications/{productSpecification}', ProductSpecificationUpdateController::class)
    ->name('api.v1.product_specifications.update');

Route::delete('product_specifications/{productSpecification}', ProductSpecificationDestroyController::class)
    ->name('api.v1.product_specifications.destroy');

Route::get('products/{product}/specifications', ProductProductSpecificationIndexController::class)
    ->name('api.v1.products.specifications.index');

Route::post('products/{product}/specifications', ProductProductSpecificationStoreController::class)
    ->name('api.v1.products.specifications.store');
