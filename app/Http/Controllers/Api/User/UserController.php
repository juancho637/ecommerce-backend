<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;

class UserController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $users = $this->user->query();
        $users = $this->eagerLoadIncludes($users, $includes)->get();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->user = $this->user->create(
                $this->user->setCreate($request)
            );

            $this->user->syncRoles($request->role);
            DB::commit();

            return $this->showOne($this->user);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $this->user = $user->setUpdate($request);
            $this->user->save();
            DB::commit();

            return $this->showOne($this->user);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $this->user = $user->setDelete();
            $this->user->save();
            DB::commit();

            return $this->showOne($this->user);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('roles', $includes)) {
            $query->with('roles');
        }

        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('agency', $includes)) {
            $query->with('agency');
        }

        if (in_array('socialNetworks', $includes)) {
            $query->with('socialNetworks');
        }

        return $query;
    }
}
