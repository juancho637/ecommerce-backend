<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group([], __DIR__ . '/api/authRoutes.php');

    Route::group([], __DIR__ . '/api/countryRoutes.php');

    Route::group([], __DIR__ . '/api/stateRoutes.php');

    Route::group([], __DIR__ . '/api/cityRoutes.php');

    Route::group([], __DIR__ . '/api/userRoutes.php');

    Route::group([], __DIR__ . '/api/categoryRoutes.php');

    Route::group([], __DIR__ . '/api/tagRoutes.php');

    Route::group([], __DIR__ . '/api/productAttributeRoutes.php');

    Route::group([], __DIR__ . '/api/productAttributeOptionRoutes.php');

    Route::group([], __DIR__ . '/api/productRoutes.php');

    Route::group([], __DIR__ . '/api/productSpecificationRoutes.php');

    Route::group([], __DIR__ . '/api/productStockRoutes.php');

    Route::group([], __DIR__ . '/api/resourceRoutes.php');
});
