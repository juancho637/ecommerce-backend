<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tag\TagShowController;
use App\Http\Controllers\Api\Tag\TagIndexController;
use App\Http\Controllers\Api\Tag\TagStoreController;
use App\Http\Controllers\Api\Tag\TagUpdateController;
use App\Http\Controllers\Api\Tag\TagDestroyController;

Route::get('tags', TagIndexController::class)
    ->name('api.v1.tags.index');

Route::post('tags', TagStoreController::class)
    ->name('api.v1.tags.store');

Route::get('tags/{tag}', TagShowController::class)
    ->name('api.v1.tags.show');

Route::match(['put', 'patch'], 'tags/{tag}', TagUpdateController::class)
    ->name('api.v1.tags.update');

Route::delete('tags/{tag}', TagDestroyController::class)
    ->name('api.v1.tags.destroy');
