<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class UserShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,user')->only('__invoke');
    }

    /**
     * Mostrar usuario
     * 
     * Muestra la información de un usuario indicado por el id.
     * 
     * @group Usuarios
     * @authenticated
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User with=status,roles,socialNetworks
     * 
     * @urlParam id int required Id del usuario.
     */
    public function __invoke(Request $request, User $user)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $user->loadEagerLoadIncludes($includes)
        );
    }
}
