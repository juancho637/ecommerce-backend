<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class UserDestroyController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,user')->only('__invoke');
    }

    /**
     * Eliminar usuario
     * 
     * Elimina un usuario indicado por el id.
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

        DB::beginTransaction();
        try {
            $this->user = $user->setDelete();
            $this->user->save();
            DB::commit();

            return $this->showOne(
                $this->user->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
