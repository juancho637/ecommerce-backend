<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\State\StateController;

Route::apiResource('states', StateController::class, [
    'as' => 'api.v1'
]);
