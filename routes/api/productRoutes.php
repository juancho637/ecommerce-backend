<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Product\ProductController;

Route::apiResource('products', ProductController::class, [
    'as' => 'api.v1',
]);
