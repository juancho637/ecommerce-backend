<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tag\TagController;

Route::apiResource('tags', TagController::class, [
    'as' => 'api.v1'
]);
