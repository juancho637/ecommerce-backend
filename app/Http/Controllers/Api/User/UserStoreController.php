<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\StoreUserRequest;

class UserStoreController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . User::class)->only('__invoke');
    }

    /**
     * Guardar usuario
     * 
     * Guarda un usuario en la aplicación.
     * 
     * @group Usuarios
     * @authenticated
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User with=status,roles,socialNetworks
     */
    public function __invoke(StoreUserRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->user = $this->user->create(
                $this->user->setCreate($request)
            );

            $this->user->syncRoles($request->role);
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
