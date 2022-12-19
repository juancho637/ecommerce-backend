<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductStock\ProductStockShowController;
use App\Http\Controllers\Api\ProductStock\ProductStockIndexController;
use App\Http\Controllers\Api\ProductStock\ProductStockUpdateController;
use App\Http\Controllers\Api\ProductStock\ProductStockDestroyController;

Route::get('product_stocks', ProductStockIndexController::class)
    ->name('api.v1.product_stocks.index');

Route::get('product_stocks/{productStock}', ProductStockShowController::class)
    ->name('api.v1.product_stocks.show');

Route::match(['put', 'patch'], 'product_stocks/{productStock}', ProductStockUpdateController::class)
    ->name('api.v1.product_stocks.update');

Route::delete('product_stocks/{productStock}', ProductStockDestroyController::class)
    ->name('api.v1.product_stocks.destroy');
