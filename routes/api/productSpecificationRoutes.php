<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationController;

Route::apiResource('product_specifications', ProductSpecificationController::class, [
    'as' => 'api.v1',
    'parameters' => [
        'product_specification' => 'productSpecification'
    ],
]);
