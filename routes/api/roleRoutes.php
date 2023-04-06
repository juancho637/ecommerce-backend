<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Role\RoleIndexController;

Route::get('roles', RoleIndexController::class)
    ->name('api.v1.roles.index');
