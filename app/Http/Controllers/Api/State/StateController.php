<?php

namespace App\Http\Controllers\Api\State;

use App\Models\State;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\State\StoreRequest;
use App\Http\Requests\Api\State\UpdateRequest;

class StateController extends ApiController
{
    private $fractal;
    private $state;

    public function __construct(Manager $fractal, State $state)
    {
        $this->fractal = $fractal;
        $this->state = $state;

        $this->middleware('auth:sanctum')->only([
            'store',
            'update',
            'destroy'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->fractal->parseIncludes($request->get('include', ''));

        $states = $this->state->query()->byRole();
        $states = $this->eagerLoadIncludes($states)->get();

        return $this->showAll($states);
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
            $this->state = $this->state->create(
                $this->state->setCreate($storeRequest)
            );
            DB::commit();

            return $this->showOne($this->state);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
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
    public function update(UpdateRequest $updateRequest, State $state)
    {
        DB::beginTransaction();
        try {
            $this->state = $state->setUpdate($updateRequest);
            $this->state->save();
            DB::commit();

            return $this->showOne($this->state);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), 400);
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
            return $this->errorResponse($exception->getMessage(), 400);
        }
    }

    protected function eagerLoadIncludes(Builder $query)
    {
        $requestedIncludes = $this->fractal->getRequestedIncludes();

        if (in_array('status', $requestedIncludes)) {
            $query->with('status');
        }

        if (in_array('country', $requestedIncludes)) {
            $query->with('country');
        }

        return $query;
    }
}
