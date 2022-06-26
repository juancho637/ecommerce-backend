<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationShowController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationIndexController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationStoreController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationUpdateController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationDestroyController;

Route::get('product_specifications', ProductSpecificationIndexController::class)
    ->name('api.v1.product_specifications.index');

Route::post('product_specifications', ProductSpecificationStoreController::class)
    ->name('api.v1.product_specifications.store');

Route::get('product_specifications/{productSpecification}', ProductSpecificationShowController::class)
    ->name('api.v1.product_specifications.show');

Route::match(['put', 'patch'], 'product_specifications/{productSpecification}', ProductSpecificationUpdateController::class)
    ->name('api.v1.product_specifications.update');

Route::delete('product_specifications/{productSpecification}', ProductSpecificationDestroyController::class)
    ->name('api.v1.product_specifications.destroy');
