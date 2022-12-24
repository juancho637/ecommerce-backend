<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Resource\ResourceStoreController;

Route::post('resources', ResourceStoreController::class)
    ->name('api.v1.resources.store');
