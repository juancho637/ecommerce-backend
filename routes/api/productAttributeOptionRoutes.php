<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionController;

Route::apiResource('product_attribute_options', ProductAttributeOptionController::class, [
    'as' => 'api.v1',
    'parameters' => [
        'product_attribute_option' => 'productAttributeOption'
    ],
]);
