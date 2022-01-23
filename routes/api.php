<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tag\TagController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\City\CityController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\State\StateController;
use App\Http\Controllers\Api\Country\CountryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\ProductAttribute\ProductAttributeController;
use App\Http\Controllers\Api\ProductSpecification\ProductSpecificationController;
use App\Http\Controllers\Api\ProductAttributeOption\ProductAttributeOptionController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/login', [AuthController::class, 'login'])
        ->name('api.v1.auth.login');
    Route::post('auth/register', [AuthController::class, 'register'])
        ->name('api.v1.auth.register');
    Route::post('/auth/{provider}', [AuthController::class, 'provider']);

    Route::apiResource('countries', CountryController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('states', StateController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('cities', CityController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('users', UserController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('categories', CategoryController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('tags', TagController::class, [
        'as' => 'api.v1'
    ]);

    Route::apiResource('product_attributes', ProductAttributeController::class, [
        'as' => 'api.v1',
        'parameters' => [
            'product_attribute' => 'productAttribute'
        ],
    ]);

    Route::apiResource('product_attribute_options', ProductAttributeOptionController::class, [
        'as' => 'api.v1',
        'parameters' => [
            'product_attribute_option' => 'productAttributeOption'
        ],
    ]);

    Route::apiResource('products', ProductController::class, [
        'as' => 'api.v1',
    ]);

    Route::apiResource('product_specifications', ProductSpecificationController::class, [
        'as' => 'api.v1',
        'parameters' => [
            'product_specification' => 'productSpecification'
        ],
    ]);
});
