<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class UserIndexController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . User::class)->only('__invoke');
    }

    /**
     * Listar usuarios
     * 
     * Lista las usuarios de la aplicación.
     * 
     * @group Usuarios
     * @authenticated
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User with=status,roles,socialNetworks
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $users = $this->user->query();
        $users = $this->eagerLoadIncludes($users, $includes)->get();

        return $this->showAll($users);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('roles', $includes)) {
            $query->with('roles');
        }

        if (in_array('socialNetworks', $includes)) {
            $query->with('socialNetworks');
        }

        return $query;
    }
}
