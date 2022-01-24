<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Category\CategoryController;

Route::apiResource('categories', CategoryController::class, [
    'as' => 'api.v1'
]);
