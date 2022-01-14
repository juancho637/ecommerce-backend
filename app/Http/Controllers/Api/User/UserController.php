<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Requests\Api\User\UpdateRequest;

class UserController extends ApiController
{
    private $fractal;
    private $user;

    public function __construct(Manager $fractal, User $user)
    {
        $this->fractal = $fractal;
        $this->user = $user;

        $this->middleware('auth:sanctum');
        $this->middleware('can:view-any,' . User::class)->only('index');
        $this->middleware('can:create,' . User::class)->only('store');
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
    }

    public function index(Request $request)
    {
        $this->fractal->parseIncludes($request->get('include', ''));

        $users = $this->user->query();
        $users = $this->eagerLoadIncludes($users)->get();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $storeRequest)
    {
        DB::beginTransaction();
        try {
            $this->user = $this->user->create(
                $this->user->setCreate($storeRequest)
            );

            $this->user->syncRoles($storeRequest->role);
            DB::commit();

            return $this->showOne($this->user);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
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
    public function update(UpdateRequest $updateRequest, User $user)
    {
        DB::beginTransaction();
        try {
            $this->user = $user->setUpdate($updateRequest);
            $this->user->save();
            DB::commit();

            return $this->showOne($this->user);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
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
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    protected function eagerLoadIncludes(Builder $query)
    {
        $requestedIncludes = $this->fractal->getRequestedIncludes();

        if (in_array('roles', $requestedIncludes)) {
            $query->with('roles');
        }

        if (in_array('status', $requestedIncludes)) {
            $query->with('status');
        }

        if (in_array('agency', $requestedIncludes)) {
            $query->with('agency');
        }

        if (in_array('socialNetworks', $requestedIncludes)) {
            $query->with('socialNetworks');
        }

        return $query;
    }
}
