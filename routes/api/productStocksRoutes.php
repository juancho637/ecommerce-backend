<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductStock\ProductStockShowController;

Route::get('product_stocks/{productStock}', ProductStockShowController::class)
    ->name('api.v1.product_stocks.show');
