<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\State\StoreStateRequest;
use App\Http\Requests\Api\State\UpdateStateRequest;

class StateController extends ApiController
{
    private $state;

    public function __construct(State $state)
    {
        $this->state = $state;

        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->authorizeResource(State::class, 'state', [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $states = $this->state->query()->byRole();
        $states = $this->eagerLoadIncludes($states, $includes)->get();

        return $this->showAll($states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStateRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->state = $this->state->create(
                $this->state->setCreate($request)
            );
            DB::commit();

            return $this->showOne($this->state);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        if ($state->validByRole()) {
            return $this->showOne($state);
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request, State $state)
    {
        DB::beginTransaction();
        try {
            $this->state = $state->setUpdate($request);
            $this->state->save();
            DB::commit();

            return $this->showOne($this->state);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        DB::beginTransaction();
        try {
            $this->state = $state->setDelete();
            $this->state->save();
            DB::commit();

            return $this->showOne($this->state);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('country', $includes)) {
            $query->with('country');
        }

        return $query;
    }
}
