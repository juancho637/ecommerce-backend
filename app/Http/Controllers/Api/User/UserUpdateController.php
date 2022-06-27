<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\UpdateUserRequest;

class UserUpdateController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,user')->only('__invoke');
    }

    /**
     * Actualizar usuario
     * 
     * Actualiza el usuario indicado por el id.
     * 
     * @group Usuarios
     * @authenticated
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User with=status,roles,socialNetworks
     * 
     * @urlParam id int required Id del usuario.
     */
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->user = $user->setUpdate($request);
            $this->user->save();
            DB::commit();

            return $this->showOne(
                $this->user
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
