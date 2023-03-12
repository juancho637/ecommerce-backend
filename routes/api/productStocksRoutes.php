<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductStock\ProductStockShowController;
use App\Http\Controllers\Api\ProductStock\ProductStockUpdateController;
use App\Http\Controllers\Api\Product\ProductStock\ProductProductStockIndexController;
use App\Http\Controllers\Api\Product\ProductStock\ProductProductStockStoreController;

Route::get('product_stocks/{productStock}', ProductStockShowController::class)
    ->name('api.v1.product_stocks.show');

Route::put('product_stocks/{productStock}', ProductStockUpdateController::class)
    ->name('api.v1.product_stocks.update');

Route::get('products/{product}/stocks', ProductProductStockIndexController::class)
    ->name('api.v1.products.stocks.index');

Route::post('product/{product}/stocks', ProductProductStockStoreController::class)
    ->name('api.v1.products.stocks.store');
