<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeController;

Route::apiResource('product_attributes', ProductAttributeController::class, [
    'as' => 'api.v1',
    'parameters' => [
        'product_attribute' => 'productAttribute'
    ],
]);
